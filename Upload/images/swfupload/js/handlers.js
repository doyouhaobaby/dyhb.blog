function fileQueued(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus(lang[60]);
		progress.toggleCancel(true, this);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        if (numFilesSelected > 0) {
            document.getElementById(this.customSettings.cancelButtonId).disabled = false;
        }
        
        /* I want auto start the upload and I can do that here */
        //this.startUpload();
    } catch (ex)  {
        this.debug(ex);
    }
}

function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert(lang[61]+"\n" + (message === 0 ? lang[62] : lang[63] + (message > 1 ? lang[64] + message + lang[65] :lang[66])));
			return;
		}

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus(lang[67]);
			this.debug(lang[68]+lang[67]+lang[72] + file.name +lang[69] + file.size + lang[70] + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus(lang[71]);
			this.debug(lang[68]+lang[71]+lang[72]+ file.name + lang[69] + file.size + lang[70] + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			progress.setStatus(lang[73]);
			this.debug(lang[68]+lang[73]+lang[72] + file.name + lang[69]+ file.size + lang[70]+ message);
			break;
		default:
			if (file !== null) {
				progress.setStatus(lang[75]);
			}
			this.debug(lang[68] + errorCode +lang[72]+ file.name + lang[69] + file.size + lang[70] + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesSelected > 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = false;
		}
		
		/* I want auto start the upload and I can do that here */
		this.startUpload();
	} catch (ex)  {
        this.debug(ex);
	}
}

function uploadStart(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus(lang[76]);
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	
	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		progress.setStatus(lang[76]);
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus(lang[77]);
		progress.toggleCancel(false);

	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus(lang[78] + message);
			this.debug(lang[68]+lang[78]+lang[72] + file.name + lang[70]+ message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus(lang[79]);
			this.debug(lang[68]+lang[79]+lang[72] + file.name + lang[69] + file.size + lang[70] + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus(lang[80]);
			this.debug(lang[68]+lang[80]+lang[72] + file.name + lang[69] + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus(lang[81]);
			this.debug(lang[68]+lang[81]+lang[72]+ file.name + lang[69] + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			progress.setStatus(lang[82]);
			this.debug(lang[68]+lang[82]+lang[72] + file.name + lang[69] + file.size + lang[70] + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			progress.setStatus(lang[83]);
			this.debug(lang[68]+lang[83]+lang[72] + file.name + lang[69] + file.size + lang[70] + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			// If there aren't any files left (they were all cancelled) disable the cancel button
			if (this.getStats().files_queued === 0) {
				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
			}
			progress.setStatus(lang[84]);
			progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus(lang[85]);
			break;
		default:
			progress.setStatus(+lang[75] + errorCode);
			this.debug(lang[68]+ errorCode +lang[72] + file.name + lang[69]+ file.size + lang[70] + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function uploadComplete(file) {
	if (this.getStats().files_queued === 0) {
		document.getElementById(this.customSettings.cancelButtonId).disabled = true;
	}
}

// This event comes from the Queue Plugin
function queueComplete(numFilesUploaded) {
	var status = document.getElementById("divStatus");
	status.innerHTML = numFilesUploaded + lang[86] + (numFilesUploaded === 1 ? "" : "s") + lang[87];
}