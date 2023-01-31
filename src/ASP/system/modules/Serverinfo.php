<?php
class Serverinfo
{
    public function Init() 
    {
		// Make sure the database if offline
		if(DB_VER == '0.0.0')
			redirect('home');
			
        // Get array
        $this->DB = Database::GetConnection();
        $result = $this->DB->query("SELECT * FROM servers ORDER BY ip ASC;");
        if($result instanceof PDOStatement) 
			$result = $result->fetchAll();
		else
			$result = array();
        
        if(isset($_GET['id']))
        {
            foreach($result as $s)
            {
                if($s['id'] == $_GET['id'])
                {
                    $this->displayServer($s);
                    die();
                }
            }
        }
        
        // Check for post data
        if(isset($_GET['ajax']))
        {
            switch($_GET['ajax'])
            {
                case "list":
                    $this->displayServerList();
                    break;
                    
                case "server":
                    $this->processServer($_POST['id']);
                    break;
                    
                case "action":
                    $this->processAction($_POST['action'], $result);
                    break;
            }
        }
        else
        {
            // Setup the template
            $Template = new Template();
            $Template->set('servers', $result);
            $Template->render('serverinfo');
        }
    }
    
    public function displayServer($server)
    {
        // Load the template
        $Template = new Template();
        
        // Load the server data
        $data = $this->loadGamespyData($server['ip'], $server['queryport']);
        if($data == false)
        {
            $Template->set('name', $server['name']);
            $Template->render('serverinfo_offline');
            return;
        }
        
        // Get our human readable army names ;)
        $data['server']['team1_name'] = $this->getArmyName($data['server']['bf2_team1']);
        $data['server']['team2_name'] = $this->getArmyName($data['server']['bf2_team2']);
        
        // Get our map Image
        $map = str_replace(' ', '_', strtolower($data['server']['mapname']));
        
        // devil's Perch Fix
        $map = str_replace('\'', '', $map);
        $location = ROOT . DS . 'frontend' . DS . 'images' . DS . 'maps' . DS;
        
        // Make sure our map file exists, or replace it with default one
        if( !file_exists($location . $map .'.png') )
            $map = 'default';
        
        // Setup the template
        $Template = new Template();
        $Template->set('map_image', $map);
        $Template->set('server', $data['server']);
        $Template->set('players_1', $data['team1']);
        $Template->set('players_2', $data['team2']);
        $Template->render('serverinfo_detailed');
    }

    public function displayServerList()
    {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array( 'id', 'publicaddress', 'ip', 'name', 'prefix', 'port', 'queryport');
        
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";
        
        /* DB table to use */
        $sTable = "servers";
        
        // Get a column count
        $aColumnCount = count($aColumns);
        
        // Get database connections
        $DB = Database::GetConnection();
        
        /* Paging */
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
            $sLimit = "LIMIT ". addslashes( $_GET['iDisplayStart'] ) .", ". addslashes( $_GET['iDisplayLength'] );
        
        /*  Ordering */
        $sOrder = "";
        if( isset( $_GET['iSortCol_0'] ) )
        {
            $sOrder = "ORDER BY  ";
            for($i = 0; $i < intval($_GET['iSortingCols']); $i++)
            {
                if( $_GET[ 'bSortable_'. intval($_GET['iSortCol_'.$i]) ] == "true" )
                {
                    $sOrder .= "`". $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."`". addslashes( $_GET['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if( $sOrder == "ORDER BY" ) $sOrder = "";
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
        {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++)
            {
                $sWhere .= "`". $aColumns[$i]."` LIKE '%". addslashes( $_GET['sSearch'] ) ."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }

        
        /* Individual column filtering */
        for($i = 0; $i < count($aColumns); $i++)
        {
            if( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
            {
                $sWhere .= ($sWhere == "") ? "WHERE " : " AND ";
                $sWhere .= "`".$aColumns[$i]."` LIKE '%". addslashes($_GET['sSearch_'.$i]) ."%' ";
            }
        }
        
        /* SQL queries, Get data to display */
        $columns = "`". str_replace(",``", " ", implode("`, `", $aColumns)) ."`";
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS {$columns} FROM {$sTable} {$sWhere} {$sOrder} {$sLimit}";
        $rResult = $DB->query( $sQuery )->fetchAll();
        
        /* Data set length after filtering */
        $iFilteredTotal = $DB->query( "SELECT FOUND_ROWS()" )->fetchColumn();
        
        /* Total data set length */
        $iTotal = $DB->query( "SELECT COUNT(`".$sIndexColumn."`) FROM   $sTable" )->fetchColumn();

        /* Output */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => intval($iTotal),
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        // Now add each row to the aaData
        foreach( $rResult as $aRow )
        {
            $row = array();
            for($i = 0; $i < $aColumnCount; $i++)
            {
                if( $aColumns[$i] == "version" )
                {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
                }
                elseif( $aColumns[$i] != ' ' )
                {
                    /* General output */
                    $row[] = $aRow[ $aColumns[$i] ];
                }
            }
            
            $row[1] = htmlspecialchars($row[1]);
            $row[2] = htmlspecialchars($row[2]);
            $row[3] = htmlspecialchars($row[3]);
            $row[4] = htmlspecialchars($row[4]);
            $row[5] = htmlspecialchars($row[5]);
            $row[6] = htmlspecialchars($row[6]);
            $row[7] = "<div id='status_{$row[0]}' style='text-align: center;'><img src='frontend/images/core/alerts/loading.gif'></div>";
            // Add 'View Server' and 'Manage' button
            $row[8] = "<a href='?task=serverinfo&id={$row[0]}'>View Server</a>&nbsp; - &nbsp;<a id='edit' name='{$row[0]}' href='#'>Manage</a>";
            $output['aaData'][] = $row;
        }
        
        echo json_encode( $output );
    }

    public function processServer($id)
    {
        // Load the database
        $DB = Database::GetConnection();

        // Process action
        switch($_POST['action'])
        {
            case 'configure':
                $this->Configure();
                break;

            case "fetch":
                // Get the server
                $query = "SELECT * FROM `servers` WHERE `id` = ". intval($id);
                $result = $DB->query( $query );
                if(!($result instanceof PDOStatement) || !is_array(($row = $result->fetch())))
                {
                    echo json_encode( array('success' => false, 'message' => "Server ID ($id) Does Not Exist!") );
                    die();
                }
                
                echo json_encode( array('success' => true) + $row );
                break;
        }
    }

    public function processAction($action, $result)
    {
        // Switch to our actions
        switch($action)
        {
            case 'status':
                $this->Process($result);
                break;
        }
    }

    public function Configure()
    {
        // Get our post data
        $publicaddress = trim($_POST['publicaddress'], "'`;");
        $port = intval($_POST['port']);
        $password = intval($_POST['password']);
        $id = intval($_POST['id']);
        
        // Load database and query
        $result = $this->DB->exec("UPDATE `servers` SET `publicaddress` = '$publicaddress', `rcon_port` = '$port', `rcon_password` = '$password' WHERE `id`=$id;");
        if($result === false)
        {
            echo json_encode( array('success' => false, 'message' => 'Error updating Rcon data in the database. Please refresh the page and try again.') );
        }
        else
        {
            echo json_encode( array('success' => true, 'message' => 'Rcon data saved Successfully!') );
        }
    }
    
    public function Process($result)
    {
        // Load the Rcon Class
        // $Rcon = new Rcon();
        $data = array();
        foreach($result as $server)
        {
            // $result = $Rcon->connect($server['ip'], $server['rcon_port'], $server['rcon_password']);

            // Open our socket to the server
            $sock = @fsockopen("udp://". $server['ip'], $server['queryport']);
            @socket_set_timeout($sock, 0, 500000);

            // Query the gamespy data
            $queryString = "\xFE\xFD\x00\x10\x20\x30\x40\xFF\xFF\xFF\x01";
            @fwrite($sock, $queryString);

            $bytes = @fread($sock, 5);
            if ($bytes == "\x00\x10\x20\x30\x40") {
                $status = '<font color="green">Online</font>';
            } else {
                $status = '<font color="red">Offline</font>';
            }
            
            // Close the connection
            // $Rcon->close();
            $data[$server['id']] = $status;
        }
        
        echo json_encode( array('data' => $data) );
    }
    
    protected function loadGamespyData($ip, $port)
    {
        // Setup our predefined vars
        $i = 1;
        $end = false;
        $Packet = array(1 => '', 2=> '', 3 => '');

        // Open our socket to the server, UDP port always open so we cant determine
        // the online status of our server yet!
        $sock = @fsockopen("udp://". $ip, $port);
        @socket_set_timeout($sock, 0, 500000);

        // Query the gamespy data
        $queryString = "\xFE\xFD\x00\x10\x20\x30\x40\xFF\xFF\xFF\x01";
        @fwrite($sock, $queryString);

        // Look through and read each of the 3 packets that get returned
        while(!$end) 
        {
            $bytes = @fread($sock, 1);
            $status = @socket_get_status($sock);
            $length = $status['unread_bytes'];

            if($length > 0)
            {
                $Info[$i] = $bytes . fread($sock, $length);

                preg_match("/splitnum(...)/is",$Info[$i],$regs);
                $String = $regs[1];

                $num = ord(substr($String,1,1));

                if($num == 128 || $num == 0) 
                {
                    $Packet[1] = $Info[$i];
                }

                if ($num == 129 || $num == 1) 
                {
                    $Packet[2] = $Info[$i];
                }

                if ($num == 130) 
                {
                    $Packet[3] = $Info[$i];
                }
            }

            if($length == 0) 
            {
                $end = true;
            }
            
            // Smaller servers will respond with only 2 packets.
            // If we got all 2 packets, and Packet 2 starts with this header, we're done. Don't need to wait for socket timeout
            if ($i == 2 && stripos($Packet[2], "\x00\x10\x20\x30@splitnum\x00\x81\x01") === 0) {
                $end = true;
            }

            // Larger servers will respond with 3 packets.
            // If we got all 3 packets, and Packet 3 starts with this header, we're done. Don't need to wait for socket timeout
            if ($i == 3 && stripos($Packet[3], "\x00\x10\x20\x30@splitnum\x00\x82\x02") === 0) {
                $end = true;
            }

            $i++;
        }

        // Close the socket and build our packet string
        @fclose($sock);
        $Info = $Packet[1] . $Packet[2] . $Packet[3];
        
        // If our string is empty, return false
        if(empty($Info)) return FALSE;
        
        // Parse our returned packets
        $output = str_replace("\\","",$Info);
        $changeChr = chr(0);
        $output = str_replace($changeChr, "\\", $output);
        $rules = "x".substr($output,0,strpos($output,"\\\\".chr(1)));
        $players = "\\".substr($output,strpos($output,"\\\\".chr(1))+3);

        $p3 = strpos($players,"\\\\".chr(2));

        if(!$p3) 
        {
            $p3 = strpos($players,"\\\\team_t");
        }
        if(!$p3) 
        {
            $p3 = strpos($players,"\�team_t");
        }

        // Parse players
        $players = $p3 ? substr($players,0,$p3) : substr($players,0);
        $players = str_replace("\\ 0@splitnum\�","",$players);
        $players = str_replace("\\ 0@splitnum\\�","",$players);
        $players = str_replace(" 0@splitnum\\","",$players);
        $players = str_replace(" 0@splitnum\\�","",$players);
        $players = str_replace("\x10\x20\x30@splitnum\\\x81\x01","",$players);
        $players = str_replace("\x10\x20\x30@splitnum\\\x82\x02","",$players);
        // Strip the cut-off prop. E.g. '\F. Liliegren\\score\\score_\\0\0' becomes '\F. Liliegren\\score_\\0'
        $players = preg_replace('/\\\\{2}[^_\\\\]+(\\\\{2}[^_\\\\]+_\\\\)/',"$1",$players);

        //Parse Rules
        $rule_temp = substr($rules,1);
        $rule_temp = str_replace("�","\\",$rule_temp);
        $rules_arr = explode("\\",$rule_temp);
        $rules_count = count($rules_arr);

        // Build our server data into a nice array
        for($i=0; $i < ($rules_count / 2); $i++) 
        {
            $r1[$i] = $rules_arr[$i*2];
            $r2[$i] = $rules_arr[($i*2)+1];
            $rule[$r1[$i]] = $r2[$i];

        }

        $tags = explode("\\",$players);

        $index = 0;
		$player = array();
        $currentProp = "";
        $newIndexFlag = false;
        $propCount = 0;
        $tagCount = count($tags) -1;

        for($i = 0; $i < $tagCount; $i++) 
        {
            if($tags[$i] == "" && substr($tags[$i+1], strlen($tags[$i+1]) -1, 1) == "_" && $tags[$i+1] != $currentProp && ord($tags[$i+2]) == 0) 
            {
                $currentProp = $tags[$i+1];
                $index = 0;
                $prop[$propCount] = $currentProp;
                $propCount++;
            } 
            else 
            {

                if($tags[$i] == $currentProp && ord($tags[$i+1]) != 0) 
                {
                    $index = ord($tags[$i+1]);
                    $newIndexFlag = true;
                } 
                else 
                {
                    if($tags[$i]!="" && $currentProp!="" && $tags[$i]!=$currentProp) 
                    {
                        $player[$currentProp][$index] = $tags[$i];
                        if($newIndexFlag) 
                        {
                            $player[$currentProp][$index] = substr($tags[$i],1);
                            $newIndexFlag = false;
                        }
                        $index++;
                    }
                }
            }
        }
        
        // Build out player list
        $data = array();
        $count = count($player['player_']);
        for ($p = 0; $p < $count; $p++) 
        {
            // Fix missing deaths bug in custom maps ??
            if(!isset($player["deaths_"][$p])) $player["deaths_"][$p] = 0;
            $data[] = array(
                'name' => $player["player_"][$p], 
                'score' => $player["score_"][$p],
                'kills' => $player["skill_"][$p],            
                'deaths' => $player["deaths_"][$p], 
                'ping' => $player["ping_"][$p], 
                'team' => $player["team_"][$p], 
                'pid' => $player["pid_"][$p],
                'ai' => $player["AIBot_"][$p]
            );
        }
        
        // Prepate our return array
        $return = array(
            'server' => $rule,
            'team1' => array(), 
            'team2' => array()
        );
        
        // Sort each player by team
        foreach($data as $player)
        {
            $return['team'. $player['team']][] = $player;
        }
        
        return $return;
    }
    
    public function getArmyName($name)
    {
        switch(strtolower($name)) 
        {
            case "mec":
                return "Middle Eastern Coalition";
                break;

            case "us":
            case "usa":
                return "United States Marine Corps";
                break;
            
            case "ch":
                return "People's Liberation Army";
                break;

            case "seal":
                return "Seals";
                break;

            case "sas":
                return "SAS";
                break;

            case "spetz":
                return "Spetsnaz";
                break;

            case "mecsf":
                return "Middle Eastern Coalition SF";
                break;

            case "chinsurgent":
            case "rebels":
                return "Rebels";
                break;

            case "meinsurgent":
            case "insurgents":
                return "Insurgents";
                break;

            case "eu":
                return "European Union";
                break;
                
            default:
                return "Unknown Army ($name)";
                break;
        }
    }
}
?>
