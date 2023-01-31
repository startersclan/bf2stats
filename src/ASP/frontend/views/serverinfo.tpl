<div class="mws-panel grid_8">
    <div class="mws-panel-header">
        <span class="mws-i-24 i-graph">Server Info</span>
    </div>
    <div class="mws-panel-body">
        <table class="mws-datatable-fn mws-table">
            <thead>
                <tr>
                    <th>Server ID</th>
                    <th>Public Address</th>
                    <th>IP</th>
                    <th>Name</th>
                    <th>Prefix</th>
                    <th>Port</th>
                    <th>Query Port</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                {servers}
                    <tr>
                        <td>{id}</td>
                        <td>{publicaddress}</td>
                        <td>{ip}</td>
                        <td>{name}</td>
                        <td>{prefix}</td>
                        <td>{port}</td>
                        <td>{queryport}</td>
                        <td><div id="status_{id}" style="text-align: center;"><img src="frontend/images/core/alerts/loading.gif"></div></td>
                        <td>
                            <center>
                                <a href="?task=serverinfo&id={id}">View Server</a>&nbsp; - &nbsp;<a id="edit" name="{id}" href="#">Manage</a>
                            </center>
                        </td>
                    </tr>
                {/servers}
            </tbody>
        </table>
        
        <!-- Hidden Ajax Thing -->
        <div id="ajax-dialog">
            <div class="mws-dialog-inner">
                <form id="mws-validate" class="mws-form" action="?task=serverinfo&ajax=server" method="POST">
                    <input type="hidden" name="action" value="configure" />
                    <input type="hidden" name="id" id="server-id"/>
                    <div id="ajax-message" style="display: none;"></div>
                    
                    <div class="mws-form-inline">
                        <div class="mws-form-row">
                            <label>Public Address:</label>
                            <div class="mws-form-item large">
                                <input id="publicaddress" type="text" name="publicaddress" class="mws-textinput" placeholder="E.g. 1.2.3.4, bf2.example.com, etc."/>
                            </div>
                        </div>
                        <div class="mws-form-row">
                            <label>Rcon Port:</label>
                            <div class="mws-form-item large">
                                <input id="rcon-port" type="text" name="port" class="mws-textinput required" />
                            </div>
                        </div>
                        <div class="mws-form-row">
                            <label>Rcon Password:</label>
                            <div class="mws-form-item large">
                                <input id="rcon-pass" type="password" name="password" class="mws-textinput" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
