let s2baiabotparameters = false;
let s2baiacpbindex = 0;
let s2baia_start_msg = "Hi! How can I help you?";
let s2baia_chatbot_messages = [{"id":s2baiaGenId(),"role":"assistant","content":s2baia_start_msg,"actor":"AI: ","timestamp":new Date().getTime()}];

let s2baia_chatbot_button_mode = 1;//send
jQuery(document).ready(function () {
      
    if(typeof s2baia_alert_log_msg_exist !== 'undefined' ){
        s2baia_chatbot_messages = [{"id":s2baiaGenId(),"role":"assistant","content":s2baia_start_msg,"actor":"AI: ","timestamp":new Date().getTime()},{"id":s2baiaGenId(),"role":"assistant","content":"","actor":"AI: ","timestamp":new Date().getTime()}];
    }


    });  
    
function s2baiaGetAIButtons(button_type) {
    let cpButtonSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#636a84"><path d="M64 464H288c8.8 0 16-7.2 16-16V384h48v64c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V224c0-35.3 28.7-64 64-64h64v48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16zM224 304H448c8.8 0 16-7.2 16-16V64c0-8.8-7.2-16-16-16H224c-8.8 0-16 7.2-16 16V288c0 8.8 7.2 16 16 16zm-64-16V64c0-35.3 28.7-64 64-64H448c35.3 0 64 28.7 64 64V288c0 35.3-28.7 64-64 64H224c-35.3 0-64-28.7-64-64z"/></svg>'
    let buttons = '';
    s2baiacpbindex = s2baiacpbindex + 1;
    buttons += '<div class="s2baia-bot-chatbot-ai-message-buttons">';
        buttons = buttons + '<div class="s2baia-bot-chatbot-ai-message-copy" title="Click to Copy" id=\'s2baia_bot_chatbot_ai_message_copy_'+s2baiacpbindex+'\' onclick="s2baiaCopyText(\'s2baia_bot_chatbot_ai_message_copy_'+s2baiacpbindex+'\',null, '+button_type+'  );" >' +cpButtonSvg + '</div>';
    buttons += '</div>';
    return buttons;
}

function s2baiaGenId(){
    return Math.random().toString(36).substring(2);
}

function s2baiaClearText(){
    s2baia_chatbot_messages = [];
    s2baia_chatbot_button_mode = 1;
    let sndbtntext = 'Send';
    if(s2baia_button_config_general_send){
        sndbtntext = s2baia_button_config_general_send;
    }
    let sendbuttonspan = document.querySelector('.s2baia-bot-chatbot-send-button span');
    if(sendbuttonspan){
        sendbuttonspan.innerHTML = sndbtntext;
    }
    let usermsgs = document.querySelectorAll('.s2baia-bot-chatbot-user-message-box');
    while(usermsgs && usermsgs.length > 0){
        usermsgs[0].parentNode.removeChild(usermsgs[0]);
        usermsgs = document.querySelectorAll('.s2baia-bot-chatbot-user-message-box');
    }
    let aimsgs = document.querySelectorAll('.s2baia-bot-chatbot-ai-message-box');
    while(aimsgs && aimsgs.length > 0){
        aimsgs[0].parentNode.removeChild(aimsgs[0]);
        aimsgs = document.querySelectorAll('.s2baia-bot-chatbot-ai-message-box');
    }
    
    
}

function s2baiaRemoveElementsByClass(className){
    const elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}

function s2baiaSendMessage(e) {
  e.preventDefault();  
  if(s2baia_chatbot_button_mode !== 1){
      s2baiaClearText();
      return;
  }
  let userInputEl = document.getElementById("s2baiabotchatbotpromptinput");
  let userInput = userInputEl.value;
  var loader = document.querySelector('.s2baia-bot-chatbot-loading-box');

  let mdiv = document.createElement("div");
  mdiv.setAttribute("class", "s2baia-bot-chatbot-user-message-box");
  mdiv.innerHTML = userInput + s2baiaGetAIButtons(2);
  let chathistory = document.querySelector('div.s2baia-bot-chatbot-messages-box');
  chathistory.appendChild(mdiv);
  userInputEl.style.height = "54px";
  let scrolledHeight = document.querySelector('.s2baia-bot-chatbot-messages-box').scrollHeight;
  let elementHeight = Math.round(document.querySelector('.s2baia-bot-chatbot-messages-box').offsetHeight);
  let s2baiaidbot = document.querySelector('#s2baiaidbot').value;
  loader.style.bottom = (10 + elementHeight - scrolledHeight)+'px';
  loader.style.display = 'block';
  chathistory.scrollTop = chathistory.scrollHeight;
  let msgitem = {"id":s2baiaGenId(),"role":"user","content":userInput,"actor":"ME: ","timestamp":new Date().getTime()};
  s2baia_chatbot_messages.push(msgitem);
  let bdy = {'messages':s2baia_chatbot_messages,'bot_id':s2baiaidbot,'message':userInput};
  fetch(s2baiabotparameters.rest_url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      "X-WP-Nonce": s2baiabotparameters.rest_nonce
    },
    body: JSON.stringify(bdy)
  })
  .then(response => response.text(),error=>{loader.style.display = 'none';})
  .then(data => {
      //console.log(data);

    let inputprompt = document.getElementById("s2baiabotchatbotpromptinput"); // clear input field
    inputprompt.value = '';
    loader.style.display = 'none';
    let chathistory = document.querySelector('div.s2baia-bot-chatbot-messages-box');
    let dataobj = false;
    try {
        
        dataobj = JSON.parse(data);
    } catch (e) {
        console.log('parse issue');
        console.log(data);
        console.log('+++++++++++');
        return false;
    }

    let mdiv = document.createElement("div");
    mdiv.setAttribute("class", "s2baia-bot-chatbot-ai-message-box");
    if('success' in dataobj && dataobj.success == true && 'reply' in dataobj){
            let reply = dataobj.reply;
            msgitem = {"id":s2baiaGenId(),"role":"assistant","content":reply,"actor":"AI: ","timestamp":new Date().getTime()};
            s2baia_chatbot_messages.push(msgitem);
            
            
            mdiv.innerHTML = '<span class="s2baia-bot-chatbot-ai-response-message">'+reply+'</span>' + s2baiaGetAIButtons(1);
            chathistory.appendChild(mdiv);
            let sendbuttonspan = document.querySelector('.s2baia-bot-chatbot-send-button span');
            if(sendbuttonspan){
                let clrbtntxt = 'Clear';
                if(s2baia_button_config_general_clear){
                    clrbtntxt = s2baia_button_config_general_clear;
                }
                sendbuttonspan.innerHTML = clrbtntxt;
                s2baia_chatbot_button_mode = 0;
            }
    }else{
        let errmsg = dataobj.message;
        mdiv.innerHTML = '<span class="s2baia-bot-chatbot-ai-response-message">'+errmsg+'</span>' + s2baiaGetAIButtons(1);
        chathistory.appendChild(mdiv);
    }
    
    chathistory.scrollTop = chathistory.scrollHeight;
    //inputprompt.focus();
    //s2baiaSetPromptFocus(inputprompt);
  });
}


function s2baiaCopyText(idelement,thbut,button_type){
    
        let thisButton = undefined;
        if(idelement.length > 0){
            thisButton = jQuery('#' + idelement);
        }else{
           thisButton =  thbut;
        }
        
        let lxt = '';
        if(button_type === 1){        
            lxt = thisButton.parents(".s2baia-bot-chatbot-ai-message-box").find('span.s2baia-bot-chatbot-ai-response-message').text();
        }else{
            lxt = thisButton.parents(".s2baia-bot-chatbot-user-message-box").text();
        }
        let el = jQuery('<textarea>').appendTo('body').val(lxt).select();
	document.execCommand('copy');
	el.remove();
        jQuery(thisButton).attr('title', 'Copied!');
    
	let copyIcon = jQuery(thisButton).html();
        jQuery(thisButton).html('<span class="s2baia-copied-result-msg">+</span>');
	setTimeout(function() {
		thisButton.html(copyIcon);
        }, 900);
    
        
  
    }
    
    
function s2baiaSetPromptFocus(inputprompt){
    let prompt = false;
    if(!inputprompt === undefined){
        prompt = inputprompt;
    }else{
        prompt = document.getElementById("s2baiabotchatbotpromptinput");
    }
    prompt.focus();
}   

(function($) {
$(document).ready(function () {
    let s2baiamaximizebtn = $('.s2baia-bot-chatbot');
    
    let s2baiabotelement = document.querySelector(".s2baia-bot-chatbot");
    if(!s2baiabotelement){//to prevent returning error on other pages where bot is not loaded
        return;
    }
    
    
    try {
        s2baiabotparameters = JSON.parse(s2baiabotelement.getAttribute("data-parameters"));
    } catch (e) {
        console.log('parse issue');
        console.log(s2baiabotelement.getAttribute("data-parameters"));
        console.log('+++++++++++');
        return false;
    }
    if(s2baiamaximizebtn){
        //let _this = s2baiamaximizebtn;
        
         s2baiamaximizebtn.find('.s2baia-bot-chatbot-resize-bttn').on('click', function () {
                        let _this = s2baiamaximizebtn;
			var container = $(this).parents('.s2baia-bot-chatbot-main-container');
			var bg = _this.find('.s2baia-bot-chatbot-maximized-bg');
			var src = $(this).attr('src');
			if (!container.hasClass('s2baia-bot-chatbot-main-container-maximized-view')) {
				$(this).attr('src', src.replace('maximize', 'minimize'));
				$(this).attr('alt', "Minimize");
				container.addClass('s2baia-bot-chatbot-main-container-maximized-view');
				bg.show();
				$('body').addClass('s2baia-bot-chatbot-disabled-scroll-body');
			} else {
				$(this).attr('src', src.replace('minimize', 'maximize'));
				$(this).attr('alt', "Maximize");
				container.removeClass('s2baia-bot-chatbot-main-container-maximized-view');
				bg.hide();
				$('body').removeClass('s2baia-bot-chatbot-disabled-scroll-body');
			}
                        //s2baiaSetPromptFocus();
		});    
               
        $('.s2baia-bot-chatbot-end-bttn').on('click' ,function () {
            let _this = s2baiamaximizebtn;
			var container = $(this).parents('.s2baia-bot-chatbot-main-container');
			var bg = _this.find('.s2baia-bot-chatbot-maximized-bg');
			if (container.hasClass('s2baia-bot-chatbot-main-container-maximized-view')) {
				bg.hide();
				$('body').removeClass('s2baia-bot-chatbot-disabled-scroll-body');
			}
			container.hide();
			_this.find('.s2baia-bot-chatbot-closed-view').show();
		});
                
          $('.s2baia-bot-chatbot-closed-view').on('click', function () {
			$(this).hide();
			var container = $('.s2baia-bot-chatbot-main-container');
			var bg = $('.ays-assistant-chatbot-maximized-bg');
			if (container.hasClass('s2baia-bot-chatbot-main-container-maximized-view')) {
				bg.show();
				$('body').addClass('s2baia-bot-chatbot-disabled-scroll-body');
			}
			container.show();

                        //s2baiaSetPromptFocus();
		});
        
        
    }
    
    $('#s2baiabotchatbotpromptinput').keydown(function (e) {
        
        if ((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey){
            s2baiaSendMessage(e);
            return;
        }
        let sendbuttonspan = document.querySelector('.s2baia-bot-chatbot-send-button span');
            if(sendbuttonspan){
                let sndbtntext = 'Send';
                if(s2baia_button_config_general_send){
                    sndbtntext = s2baia_button_config_general_send;
                }
                sendbuttonspan.innerHTML = sndbtntext;
                s2baia_chatbot_button_mode = 1;
            }
        
        
    });
    
    
    
    $('.s2baia-bot-chatbot-ai-message-copy').on('click', function(){
			    let thisButton = $(this);
                            s2baiaCopyText('',thisButton);
  
		});
                
    let s2baiapromptinput = document.querySelector('#s2baiabotchatbotpromptinput');
    
    if(s2baiapromptinput){
            s2baiapromptinput.addEventListener('focus', function() {
                    let sendbuttonspan = document.querySelector('.s2baia-bot-chatbot-send-button span');
                    if(sendbuttonspan){
                        let sndbtntext = 'Send';
                        if(s2baia_button_config_general_send){
                            sndbtntext = s2baia_button_config_general_send;
                        }
                        sendbuttonspan.innerHTML = sndbtntext;
                        s2baia_chatbot_button_mode = 1;
                    }
            });
    }
	});
		
})(jQuery);           

