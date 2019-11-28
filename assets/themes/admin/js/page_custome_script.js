
var table;
// var csrf_token  = $(input[name="csrf_token"]);
//var csrf_token  = "";
//alert(csrf_token);
var csrfName = $('#csrf_hash').val();
var csrf_token = $('#csrf_token').val();
// alert(csrf_token);
$(document).ready(function() {
 
    //datatables
    table = $('#table').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
                "ajax": {
            "url": "page/page_list",
        


            "type": "POST",
            "data": {csrf_token:csrfName},
            },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0,3,5,6 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
 
    });

    // $('#image_upload').ssi_uploader({url: 'uploadfile', dropZone: false});

});



        // Load data for the table's content from an Ajax source
    Dropzone.autoDiscover = false;
 $(function() {

    
    var myDropzone = $("#imageupload").dropzone({ 
        url: "uploadfile",
        maxFilesize: 5,
        maxFiles: 5,
        renameFile: function(file) {
            var dt = new Date()
            var time = dt.getTime()
            return time+convertToSlug(file.name)
        },
        addRemoveLinks: true,
        dictResponseError: 'Server not Configured',
        acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
        timeout: 50000,

        removedfile: function(file) 
        {   //alert('asdfasdf');
            var name = file.upload.filename;
            //console.log(name);
            $.ajax({
                type: 'POST',
                url: 'deletefile',
                data: {filename: name,'_token': '{{ csrf_token() }}' },
            
                success: function (data){

                    data = JSON.parse(data);
                    var event_images = $("#page_images").val();

                    if(event_images.indexOf(','+data.name) != -1)
                    {
                         var imagevaluenull = event_images.replace(','+data.name,'');
                    }
                    else
                    {
                         var imagevaluenull = event_images.replace(data.name,'');
                    }   

                     $("#page_images").val('');
                     $("#page_images").val(imagevaluenull);  

                    // alert(imagevaluenull);
                    // var remove_img = data+',';
                    // if(event_images.indexOf(remove_img) != -1){
                    //         event_images = event_images.replace(remove_img,'');
                    //     }

                    // if(event_images.indexOf(data) != -1)
                    // {
                    //     event_images = event_images.replace(data,'');
                    // }
                    // var lastChar = event_images[event_images.length -1];
                    // if(lastChar==',')
                    // {  
                    //     var event_images = event_images.substring(0, event_images.length-1);
                    // }

                    //  $("#page_images").val('');
                    //  $("#page_images").val(event_images);  

                },
                error: function(e) {
                    // console.log(e)
                }})
                var fileRef
                return (fileRef = file.previewElement) != null ? 
                fileRef.parentNode.removeChild(file.previewElement) : void 0
        },
   
        success: function(file, response) 
        {
            response = JSON.parse(response);

            var event_images = $("#page_images").val();
                if(event_images =="")
                {
                    event_images = response.name; 
                }
                else
                {
                   event_images = event_images+','+response.name;  
                } 
            $("#page_images").val('');
            $("#page_images").val(event_images);  
        },

        error: function(file, response)
        {
           return false;
        },

        init:function() {

            var self = this
            // config
            self.options.addRemoveLinks = true
            self.options.dictRemoveFile = "Delete"
            //New file added
            self.on("addedfile", function (file) {
                // console.log('new file added ', file)
            })
            // Send file starts
            self.on("sending", function (file, xhr, formData) {
                formData.append('_token', '{{ csrf_token() }}')
                // console.log('upload started', file)
                $('.meter').show()
            })

            // File upload Progress
            self.on("totaluploadprogress", function (progress) {
                console.log("progress ", progress)
                $('.roller').width(progress + '%')
            })

            self.on("queuecomplete", function (progress) {
                $('.meter').delay(999).slideUp(999)
            })

            // On removing file
            self.on("removedfile", function (file) {
                // console.log(file)
            })

            self.on("maxfilesexceeded", function(file){
                alert("No more files please!")
                this.removeFile(file)
            })
        }
    })
})

 function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        //.replace(/[^\w-]+/g,'')
}