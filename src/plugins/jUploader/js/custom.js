
			jQuery('.inputWrapper').uploader({
				sName: 'uploader',
				bDebug: true,
				oBrowse: jQuery('#btnBrowse'),
				oUpload: jQuery('#btnSubmit'),
				oReset: jQuery('#btnReset'),
				fMaxFilesize: 8000,
				fMaxQueueSize: 100000,
				sMovie: siteurl + '/plugins/jUploader/uploader.swf',
				sBackendScript: siteurl + '/plugins/jUploader/',
				sFileFilters: [
								'All files (*.*)|*.*',
								'Images (*.gif,*.png,*.jpg)|*.gif;*.png;*.jpg'
							],
				aCallback: {
					UploaderInitialized: function(oUI) {
							// Flash movie succesfully created, now remove old file objects
							jQuery('.inputWrapper').remove();

							// And show the UI upload
							jQuery('.Uploader').show();
						},
					dialogPre: function(oUI) {
							$.ui.uploader.log(0, 'please choose some files (or show greybox)!');
						},
					dialogPost: function(oUI) {
							$.ui.uploader.log(0, 'you have hidden the dialog and you ' + ((oUI.success == true) ? 'DID' : 'DID NOT') + ' select files!!');
						},
					queueErrorCount: function(oUI) {
							$.ui.uploader.log(2, 'The file you\'ve selected (' + oUI.oFile.name + ') does not fit into the current queue (exceeds max. file count)');
						},
					queueErrorSize: function(oUI) {
							$.ui.uploader.log(2, 'The file you\'ve selected (' + oUI.oFile.name + ') does not fit into the current queue (exceeds max. queue size)');
						},
					fileErrorExtension: function(oUI) {
							$.ui.uploader.log(2, 'The file you\'ve selected (' + oUI.oFile.name + ') exceeds the maximum filesize of ' + (oUI.options.fMaxFilesize * 1024) + ' bytes.');
						},
					fileErrorSize: function(oUI) {
							$.ui.uploader.log(2, 'The file you\'ve selected (' + oUI.oFile.name + ') exceeds the maximum filesize of ' + (oUI.options.fMaxFilesize * 1024) + ' bytes.');
						},
					fileAdded: function(oUI) {
							sHTML = '<div class="UploaderItem" id="' + oUI.oFile.id + '">';
							sHTML+= '<div class="UploaderItemWrapper">';
							sHTML+= '<a href="javascript://" title="Delete upload">' + oUI.oFile.name + '</a> <span class="cbInformation">(' + $.ui.uploader.formatSize(oUI.oFile.size) + ')</span>';
							sHTML+= '<div class="UploaderProgress"><div class="UploaderProgressBar"> 0% </div></div>';
							sHTML+= '&#0187; <strong>Processed:</strong> <span class="cBytesProcessed">0 bytes</span> of <span class="cBytesTotal">' + $.ui.uploader.formatSize(oUI.oFile.size) + '</span> <span class="cSpeedTime">&nbsp;</span>';
							sHTML+= '</div>';
							oHTML = jQuery(sHTML);
							jQuery('.UploaderQueue').append(oHTML);

							// Add CSS class
							jQuery('A', oHTML).attr('class', 'uploadDelete');

							// Add handler
							var _oUI = oUI;
							jQuery('A', oHTML).click(function() {
									// Callback to Flash to remove the file from the queue
									jQuery('#' + _oUI.options.sName)[0].fileCancel(_oUI.oFile.id);

									// If the file was succesfully removed, Flash calls back to Javascript
									return false;
								});
						},
					fileRemoved: function(oUI) {
							jQuery('#' + oUI.oFile.id).remove();
							$.ui.uploader.log(0, 'The file you\'ve selected (' + oUI.oFile.name + ') was removed from the queue');
						},
					queueErrorEmptyUpload: function(oUI) {
							$.ui.uploader.log(2, 'Cannot upload! Current upload queue is empty!');
						},
					queueErrorEmptyCancel: function(oUI) {
							$.ui.uploader.log(2, 'Cannot cancel! Current upload queue is empty!');
						},
					queueStarted: function(oUI) {
							$.ui.uploader.log(0, 'Uploading of current queue started!');
						},
					fileStarted: function(oUI) {
							jQuery('#' + oUI.oFile.id).addClass('active').animate({ height: 80 }, 200);
							jQuery('span.cbInformation', '#' + oUI.oFile.id).html('&nbsp;');
						},
					fileErrorIO: function(oUI) {
							$.ui.uploader.log(2, 'The file you\'ve selected (' + oUI.oFile.name + ') raised an I/O error.');
						},
					fileErrorSecurity: function(oUI) {
							$.ui.uploader.log(2, 'The file you\'ve selected (' + oUI.oFile.name + ') raised an security error.');
						},
					fileErrorHTTP: function(oUI) {
							$.ui.uploader.log(2, 'The file you\'ve selected (' + oUI.oFile.name + ') gave an HTTP error while uploading: HTTP 1.1/' + oUI.errCode);
						},
					fileProgress: function(oUI) {
							sStatus = 'Upload queue: uploading ' + oUI.oProgress.qIndex + ' of ' + oUI.oProgress.qCount + ' files. ';
							sStatus+= $.ui.uploader.formatSize(oUI.oProgress.qbDone) + ' at ';
							sStatus+= $.ui.uploader.formatSize(oUI.oProgress.qbSpeed) + '/sec (' + oUI.oProgress.qProgress + '%) ';
							sStatus+= $.ui.uploader.formatTime(oUI.oProgress.qtRemain) + ' remaining...';
							jQuery('h2.UploaderStatus').html(sStatus);

							// Other shite
							jQuery('div.UploaderProgressBar', '#' + oUI.oFile.id).css('width', oUI.oProgress.cProgress + '%').html('&nbsp;' + oUI.oProgress.cProgress + '%&nbsp;');
							jQuery('span.cBytesProcessed', '#' + oUI.oFile.id).html($.ui.uploader.formatSize(oUI.oProgress.cbDone));
							jQuery('span.cSpeedTime', '#' + oUI.oFile.id).html('at ' + $.ui.uploader.formatSize(oUI.oProgress.cbSpeed) + '/sec; ' + $.ui.uploader.formatTime(oUI.oProgress.ctRemain));
						},
					fileCancelled: function(oUI) {
							jQuery('#' + oUI.oFile.id).remove();
							$.ui.uploader.log(0, 'The upload of the file you\'ve selected (' + oUI.oFile.name + ') was cancelled');
						},
					fileCompleted: function(oUI) {
							jQuery('#' + oUI.oFile.id).removeClass('active');//.animate({ height: 40 }, 200);
							jQuery('span.cbInformation:eq(0)', '#' + oUI.oFile.id).html('(' + $.ui.uploader.formatSize(oUI.oFile.size) + ' uploaded at ' + $.ui.uploader.formatSize(oUI.oProgress.cbSpeed) + '/sec; ' + $.ui.uploader.formatTime(oUI.oProgress.ctBusy) + ')');
							jQuery('A', '#' + oUI.oFile.id).unbind("click").attr("href", siteurl + "/uploads/" + oUI.oFile.name).attr("target", "_blank").attr('class', 'uploadSuccess');
						},
					queueCancelled: function(oUI) {
							$.ui.uploader.log(0, 'complete current queue cancelled!');
						},
					queueCompleted: function(oUI) {
							sStatus = 'Upload queue: Uploaded ' + oUI.oProgress.qIndex + ' of ' + oUI.oProgress.qCount + ' files. ';
							sStatus+= $.ui.uploader.formatSize(oUI.oProgress.qbDone) + ' at ';
							sStatus+= $.ui.uploader.formatSize(oUI.oProgress.qbSpeed) + '/sec (' + oUI.oProgress.qProgress + '%)';
							jQuery('h2.UploaderStatus').html(sStatus);

							$.ui.uploader.log(0, 'complete current queue (' + oUI.oProgress.qCount + ' items) uploaded!');

							//setTimeout(function() { jQuery('form#frmUpload')[0].submit(); }, 2000);
						}
					}
			});