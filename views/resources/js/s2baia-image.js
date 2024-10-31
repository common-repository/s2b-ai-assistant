let s2baiaImage = {  
  s2baiaNumberImages: null,
  s2baiaImageSaveBtn:null,
  s2baiaImageGenerateBtn:null,
  s2baiaImageConvertBar:null,
  s2baiaImageLoading:null,
  s2baiaImageGrid:null,
  /*s2baiaImageSelectAll:null,*/
  s2baiaStartTime:null,
  s2baiaImageMessage:null,
  s2baiaImageGenerated:null,
  init: function(){
      that = this;
      s2baiaImageForm = document.querySelector("#s2baia_img_gen_form");
      this.s2baiaImageGenerated = s2baiaImageForm.getElementsByClassName('image-generated')[0];
            this.s2baiaImageGrid = s2baiaImageForm.getElementsByClassName('image-grid')[0];
            this.s2baiaImageLoading = s2baiaImageForm.getElementsByClassName('image-generate-loading')[0];
            this.s2baiaImageSaveBtn = s2baiaImageForm.getElementsByClassName('image-generator-save')[0];
            this.s2baiaImageMessage = s2baiaImageForm.getElementsByClassName('s2baia_message')[0];
            this.s2baiaImageConvertBar = s2baiaImageForm.getElementsByClassName('s2baia-convert-bar')[0];
            this.s2baiaNumberImages = s2baiaImageForm.querySelector('select[name=s2baia_images_count]');
            this.s2baiaImageGenerateBtn = s2baiaImageForm.querySelector('#s2baia_submit');

            
        s2baiaImageForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var form_action = s2baiaImageForm.querySelectorAll('input[name=action]')[0].value;
                
                var num_images = parseInt(that.s2baiaNumberImages.value);
                if (num_images > 0) {
                        let imgform = new FormData(s2baiaImageForm);
                        let urlpars = new URLSearchParams(imgform);
                        let queryString = urlpars.toString();
                        that.s2baiaImageSaveBtn.style.display = 'none';
                        s2baiaImageLoadingEffect(that.s2baiaImageGenerateBtn);
                        that.s2baiaImageConvertBar.style.display = 'none';
                        that.s2baiaImageLoading.style.display = 'flex';
                        that.s2baiaImageGrid.innerHTML = '';
                        //that.s2baiaImageSelectAll.style.display = 'none';
                        let s2baiaImageError = document.getElementsByClassName('s2baia-image-error');
                        if (s2baiaImageError.length) {
                            s2baiaImageError[0].remove();
                        }
                        
                        that.s2baiaStartTime = new Date();
                        that.image_generator(queryString, 1, num_images, false, form_action);


                } else {
                    alert(s2baiaParams.languages.error_image);
                }
                return false;
            });
            this.s2baiaImageSaveBtn.addEventListener('click', function (e) {
                var items = [];
                document.querySelectorAll('.s2baia-image-item input[type=checkbox]').forEach(function (item) {
                    if (item.checked) {
                        items.push(item.getAttribute('data-id'));
                    }
                });
                if (items.length) {
                    that.s2baiaImageConvertBar.style.display = 'block';
                    that.s2baiaImageConvertBar.classList.remove('s2baia_error');
                    that.s2baiaImageConvertBar.getElementsByTagName('small')[0].innerHTML = '0/' + items.length;
                    that.s2baiaImageConvertBar.getElementsByTagName('span')[0].style.width = 0;
                    that.s2baiaImageMessage.innerHTML = '';
                    s2baiaImageLoadingEffect(that.s2baiaImageSaveBtn,'.s2baia-img-loader2');
                    that.save_image(items, 0);
                } else {
                    alert(s2baiaParams.languages.select_save_error);
                }
            });
  },
  image_generator : function(data, start, max, multi_steps,form_action){
        let that = this;
        const xhttp = new XMLHttpRequest();
        xhttp.open('POST', s2baiaParams.ajax_url);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        
        xhttp.send(data);
        xhttp.onreadystatechange = function(oEvent) {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    var s2baia_response = this.responseText;
                    res = JSON.parse(s2baia_response);
                    if(res.status === 'success'){
                        for(var idx = 0; idx < res.imgs.length; idx++){
                            let idImageBox = idx;
                            if(multi_steps){
                                idImageBox = start -1;
                            }
                            var img = res.imgs[idx];
                            var html = '<div id="s2baia-image-item-'+idImageBox+'" class="s2baia-image-item s2baia-image-item-'+idx+'" data-id="'+idImageBox+'">';
                            if(s2baiaParams.logged_in === '1') {
                                html += '<label><input data-id="' + idImageBox + '" class="s2baia-image-item-select" type="checkbox" name="image_url" value="' + img + '"></label>';
                            }
                            html += '<input value="'+res.title+'" class="s2baia-image-item-alt" type="hidden" name="image_alt">';
                            html += '<input value="'+res.title+'" class="s2baia-image-item-title" type="hidden" name="image_title">';
                            html += '<input value="'+res.title+'" class="s2baia-image-item-caption" type="hidden" name="image_caption">';
                            html += '<input value="'+res.title+'" class="s2baia-image-item-description" type="hidden" name="image_description">';
                            html += '<img  onclick="s2baiaImage.imageZoom(' + idImageBox + ')" src="' + img + '">';
                            html += '</div>';
                            that.s2baiaImageGrid.innerHTML += html;
                        }
                        if(multi_steps){
                            if(start === max){
                                s2baiaImageRmLoading(that.s2baiaImageGenerateBtn);
                                //that.s2baiaImageSelectAll.classList.remove('selectall')
                                //that.s2baiaImageSelectAll.innerHTML = s2baiaSelectAllText;
                                //that.s2baiaImageSelectAll.style.display = 'block';
                                that.s2baiaImageLoading.style.display = 'none';
                                that.s2baiaImageSaveBtn.style.display = 'block';
                            }
                            else{
                                that.image_generator(data, start+1, max, multi_steps,form_action)
                            }
                        }
                        else{
                            if(form_action === 's2baia_image_generator'){
                                let endTime = new Date();
                                let timeDiff = endTime - that.s2baiaStartTime;
                                timeDiff = timeDiff/1000;
                                data += '&action=s2baia_image_log&duration='+timeDiff+'&_wpnonce_image_log='+s2baiaImageNonce+'';
                                const xhttp = new XMLHttpRequest();
                                xhttp.open('POST', s2baiaParams.ajax_url);
                                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                xhttp.send(data);
                                xhttp.onreadystatechange = function (oEvent) {
                                    if (xhttp.readyState === 4) {

                                    }
                                }
                            }
                            s2baiaImageRmLoading(that.s2baiaImageGenerateBtn);
                            //that.s2baiaImageSelectAll.classList.remove('selectall');
                            //that.s2baiaImageSelectAll.style.display = 'block';
                            that.s2baiaImageLoading.style.display = 'none';
                            that.s2baiaImageSaveBtn.style.display = 'block';
                        }
                    }
                    else{
                        s2baiaImageRmLoading(that.s2baiaImageGenerateBtn);
                        that.s2baiaImageLoading.style.display = 'none';
                        let errorMessage = document.createElement('div');
                        errorMessage.style.color = '#f00';
                        errorMessage.classList.add('s2baia-image-error');
                        errorMessage.innerHTML = res.msg;
                        that.s2baiaImageGenerated.prepend(errorMessage);
                        setTimeout(function (){
                            errorMessage.remove();
                        },3000);
                    }
                }
                else{
                    that.s2baiaImageLoading.style.display = 'none';
                    s2baiaImageRmLoading(that.s2baiaImageGenerateBtn);
                    alert('Something went wrong');
                }
                
            }
        }
        
    

    },
    
        save_image : function(items,start){
        let that = this;
        if(start >= items.length){
            that.s2baiaImageConvertBar.getElementsByTagName('small')[0].innerHTML = items.length+'/'+items.length;
            that.s2baiaImageConvertBar.getElementsByTagName('span')[0].style.width = '100%';
            that.s2baiaImageMessage.innerHTML = s2baiaParams.languages.save_image_success;
            s2baiaImageRmLoading(that.s2baiaImageSaveBtn,'.s2baia-img-loader2');
            setTimeout(function (){
                that.s2baiaImageMessage.innerHTML = '';
            },4000)
        }
        else{
            var id = items[start];
            var item = document.getElementById('s2baia-image-item-'+id);
            var data = 'action=s2baia_save_image_media';
            data += '&image_alt='+item.querySelectorAll('.s2baia-image-item-alt')[0].value;
            data += '&image_title='+item.querySelectorAll('.s2baia-image-item-title')[0].value;
            data += '&image_caption='+item.querySelectorAll('.s2baia-image-item-caption')[0].value;
            data += '&image_description='+item.querySelectorAll('.s2baia-image-item-description')[0].value;
            data += '&image_url='+encodeURIComponent(item.querySelectorAll('.s2baia-image-item-select')[0].value);
            data +='&nonce='+s2baiaImageSaveNonce;
            const xhttp = new XMLHttpRequest();
            xhttp.open('POST', s2baiaParams.ajax_url);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(data);
            xhttp.onreadystatechange = function(oEvent) {
                if (xhttp.readyState === 4) {
                    if (xhttp.status === 200) {
                        var s2baia_response = this.responseText;
                        res = JSON.parse(s2baia_response);
                        if(res.status === 'success'){
                            var currentPos = start+1;
                            var percent = Math.ceil(currentPos*100/items.length);
                            that.s2baiaImageConvertBar.getElementsByTagName('small')[0].innerHTML = currentPos+'/'+items.length;
                            that.s2baiaImageConvertBar.getElementsByTagName('span')[0].style.width = percent+'%';
                            that.save_image(items, start+1);
                        }
                        else{
                            that.s2baiaImageConvertBar.classList.add('s2baia_error');
                            s2baiaImageRmLoading(that.s2baiaImageSaveBtn,'.s2baia-img-loader2');
                            alert(res.msg);
                        }
                    } else {
                        alert(s2baiaParams.languages.wrong);
                        that.s2baiaImageConvertBar.classList.add('s2baia_error');
                        s2baiaImageRmLoading(that.s2baiaImageSaveBtn);
                    }
                }
                
                document.querySelectorAll('.s2baia_modal_content')[0].innerHTML = '';
                let lngth = 'length';
                let s2baia_overlay = document.querySelectorAll('.s2baia-overlay');
                console.log(typeof s2baia_overlay);
                if(s2baia_overlay &&  lngth in s2baia_overlay && s2baia_overlay.length > 0){
                    s2baia_overlay[0].style.display = 'none';
                }
                let s2baia_modal = document.querySelectorAll('.s2baia_modal');
                if(s2baia_modal && lngth in s2baia_modal && s2baia_modal.length > 0){
                    s2baia_modal[0].style.display = 'none';
                }
                
            }
        }
    },
    imageZoom: function (id){
        var item = document.getElementById('s2baia-image-item-'+id);
        var alt = item.querySelectorAll('.s2baia-image-item-alt')[0].value;
        var title = item.querySelectorAll('.s2baia-image-item-title')[0].value;
        var caption = item.querySelectorAll('.s2baia-image-item-caption')[0].value;
        var description = item.querySelectorAll('.s2baia-image-item-description')[0].value;
        var url = item.querySelectorAll('input[type=checkbox]')[0].value;
        document.querySelectorAll('.s2baia_modal_content')[0].innerHTML = '';
        document.querySelectorAll('.s2baia-overlay')[0].style.display = 'block';
        document.querySelectorAll('.s2baia_modal')[0].style.display = 'block';
        document.querySelectorAll('.s2baia_modal_title')[0].innerHTML = s2baiaParams.languages.edit_image;
        var html = '<div class="s2baia_grid_form">';
        html += '<div class="s2baia_grid_form_2"><img src="'+url+'" style="width: 100%"></div>';
        html += '<div class="s2baia_grid_form_1">';
        html += '<p><label>'+s2baiaParams.languages.alternative+'</label><input type="text" class="s2baia_edit_item_alt" style="width: 100%" value="'+alt+'"></p>';
        html += '<p><label>'+s2baiaParams.languages.title+'</label><input type="text" class="s2baia_edit_item_title" style="width: 100%" value="'+title+'"></p>';
        html += '<p><label>'+s2baiaParams.languages.caption+'</label><input type="text" class="s2baia_edit_item_caption" style="width: 100%" value="'+caption+'"></p>';
        html += '<p><label>'+s2baiaParams.languages.description+'</label><textarea class="s2baia_edit_item_description" style="width: 100%">'+description+'</textarea></p>';
        html += '<p><div class="s2baia-custom-loader s2baia-modal-loader" style="display: none;"></div></p>';
        html += '<button onclick="s2baiaSaveImageData('+id+')" data-id="'+id+'" class="button button-primary s2baia_edit_image_save" type="button">'+s2baiaParams.languages.save+'</button>';
        html += '</div>';
        html += '</div>';
        document.querySelectorAll('.s2baia_modal_content')[0].innerHTML = html;
        s2baiaImageCloseModal();
    }
    }
    
    window['s2baiaImage'] = s2baiaImage.init();