<div class="new_post_main_wraper">
	<div class="new_post_main_cont">
		<p class="new_post_title">New post</p>
		<div class="new_post_type_s">
			<div id="new_images" class="new_post_type_a" onClick="showNewPost(this,'image')">Images</div>
			<div id="new_video" onClick="showNewPost(this,'video')">Video</div>
			<div id="new_text" onClick="showNewPost(this,'text')">Text</div>
			<span style="clear:both"></span>
		</div>
		<div class="new_pos_video_main">
			<div class="new_post_info_cont">
				<p>Post title<span style="font-weight:100">*</span></p>
				<input type="text" />
				<p>Tags<span style="font-weight:100">*</span></p>
				<input type="text" />		
				<p>Post description</p>
				<textarea rows="2" ></textarea> 								
			</div>		
			<div class="new_pos_video_cont">
				<p>URL to video<span style="font-weight:100">*</span> <span class="allow_web_video">  (Youtube only)</span></p>
				<input id="new_post_vid_url" type="text" />	

			</div>
			<div class="new_post_submit_cont">
				<div class="np_img_submit_btn" onclick="return false">
					Submit
				</div>		
				<div class="np_img_cancel_btn" onclick="slideDownPostContBtn()">
					Cancel
				</div>							
			</div>						
		</div>
		<div class="new_pos_text_main">
			<div class="new_post_info_cont">
				<p>Post title<span style="font-weight:100">*</span></p>
				<input type="text" />
				<p>Tags<span style="font-weight:100">*</span></p>
				<input type="text" />		
				<p>Post description</p>
				<textarea rows="2" style="height:114px" ></textarea> 								
			</div>	
			<div class="new_post_submit_cont">
				<div class="np_img_submit_btn" onclick="return false">
					Submit
				</div>		
				<div class="np_img_cancel_btn" onclick="slideDownPostContBtn()">
					Cancel
				</div>							
			</div>							
		</div>				
		<div class="new_pos_images_main">
			<div class="np_drag_img_cont">
				<p><span>add images...</span></p>
				<div class="drag_imginfo">DRAG IMAGES INTO BROWSER</div>		
				<div class="new_post_submit_cont">
					<div class="np_img_submit_btn" onclick="return false">
						Submit
					</div>		
					<div class="np_img_cancel_btn" onclick="slideDownPostContBtn()">
						Cancel
					</div>							
				</div>						
			</div>
			<div class="new_pos_images">
				<div class="new_post_info_cont">
					<p>Post title<span style="font-weight:100">*</span></p>
					<input type="text" />
					<p>Tags<span style="font-weight:100">*</span></p>
					<input type="text" />		
					<p>Post description</p>
					<textarea rows="2" ></textarea> 								
				</div>
				<div class="add_img_btn_cont">
					<p><span>add image...</span> (you can drag image into browser)</p>					
				</div>						
				<div class="new_post_img_info">
					<div class="new_post_img_cont">
						<img src="./pics/k1.jpg" alt="" />
					</div>
					<div class="new_post_img_descr">
						<p>Image description</p>
						<textarea rows="2" ></textarea>
					</div>
					<div style="clear:both"></div>
					<div class="new_post_clear_btn">clear</div>
				</div>
				<div class="new_post_img_info">
					<div class="new_post_img_cont">
						<img src="./pics/k2.jpg" alt="" />
					</div>
					<div class="new_post_img_descr">
						<p>Image description</p>
						<textarea rows="2" ></textarea>
					</div>
					<div style="clear:both"></div>
					<div class="new_post_clear_btn">clear</div>
				</div>
				<div class="new_post_img_info">
					<div class="new_post_img_cont">
						<img src="./pics/k3.jpg" alt="" />
					</div>
					<div class="new_post_img_descr">
						<p>Image description</p>
						<textarea rows="2" ></textarea>
					</div>
					<div style="clear:both"></div>
					<div class="new_post_clear_btn">clear</div>
				</div>	
				<div class="new_post_img_info">
					<div class="new_post_img_cont">
						<img src="./pics/25.jpg" alt="" />
					</div>
					<div class="new_post_img_descr">
						<p>Image description</p>
						<textarea rows="2" ></textarea>
					</div>
					<div style="clear:both"></div>
					<div class="new_post_clear_btn">clear</div>
				</div>	
				<div class="new_post_submit_cont">
					<div class="np_img_submit_btn" onclick="return false">
						Submit
					</div>		
					<div class="np_img_cancel_btn" onclick="slideDownPostContBtn()">
						Cancel
					</div>							
				</div>
			</div>
		</div>
	</div>
	<div style="height:20px"></div>
</div>