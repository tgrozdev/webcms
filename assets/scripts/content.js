jQuery(document).ready(function () {  
    var maxuploadsize = jQuery("#maxpostsize").val();
    var page_id = jQuery("#pageid").val();

    function LoadPhoto(file){
        var fr = new FileReader();
        fr.onload = function () { 
            jQuery.post('/admin.php',
                {
                    'page': 'content',
                    'section': 'upload',
                    'pageid': page_id,
                    'filename': file["name"],
                    'data': fr.result
                }, function(result){
                    //console.log(result);
                    if(result.ok){
                        jQuery("#images").append('<img class="img-fluid" style="max-width: 400px;" src="'+result.data["fullpath"]+'" ><br/>URL: '+result.data["fullpath"]+'<br/>');
                    } else {
                        alert("ERROR: "+result);
                    }
            });            
        }
        fr.readAsDataURL(file);        
    }

    var filehandler=function() {
        console.log(this.files);
        // FileReader support
        if (FileReader && this.files && this.files.length) {
            for (let i = 0; i < this.files.length; i++) {
                console.log(this.files[i]['name']);
                if(this.files[i]['size']>maxuploadsize){
                    alert("ERROR: "+this.files[i]['name']+" is larger than maximum allowed upload file size!! Please select other or compress!!! ");                
                } else {
                    // process file                    
                    LoadPhoto(this.files[i]);                    
                }
            }
        } 
        

        jQuery("#photo").val(null);    
    }
    
    jQuery("#photo").bind('change', filehandler);
});