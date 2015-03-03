 <?
	CMain::includeClass('bootstrap.CBootstrap');
	CBootstrap::getIcon();
	f_js_file(CMain::componentPath().'/js/jquery.damnUploader.min.js');
	f_js_file(CMain::componentPath().'/js/interafce.js');
	f_js_file(CMain::componentPath().'/js/uploader-setup.js');
 ?>

	<div class="image_uploader_main">
    <div >


        <div class="row">
            <div class="tab-content" style="margin-top: 10px;">

                <!-- Filesystem tab -->
                <div  id="filesystem-tab">
                    <div class="drop_box"  id="drop-box"  data-placement="bottom">
                        <form  role="form" id="upload-form" method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="ajax_image_upload" value="1" />
                            <div >
                                <input type="file"  id="file-input" name="my-file" />
                            </div>
                            <div >
                                <label>
                                    <input type="checkbox" id="previews-checker" checked="checked" /> Generate previews
                                </label>
                                <br/>
                                <label>
                                    <input type="checkbox" id="autostart-checker" /> Autostart
                                </label>
                            </div>
                            <button id="send-btn" type="submit" class="btn btn-primary btn-std pull-right">Send</button>
                            <button id="clear-btn" class="btn btn-danger btn-std pull-right">Clear</button>
                        </form>
                    </div>
                </div>


            </div>
        </div>

        <div >
            <h3>Upload queue</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Original filename</th>
                        <th>Size</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="upload-rows"></tbody>
            </table>
        </div>

    </div><!-- /.container -->
</div>