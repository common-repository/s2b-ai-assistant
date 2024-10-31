let s2baiaRowsOptions = {
    row_Loadnonce: 'variable s2b_gpt_loadnoncec',
    selected_Span: '#s2baia_selected_span_',
    selected_Href:'#s2baia_selected_href_',
    table_ElementId:'#s2baia_instructions',
    search_SubmitElementId:'#s2baia_search_submit',
    message_LogConfirmDelete:'',/*s2b_message_log_confirm_delete,*/
    row_DellogNonce:'',/*s2b_bot_dellognonce*/
    row_Href_Prefix:'',
    row_PageId:'#s2baia_page_log',
    rows_PerPageId:'#logs_per_page',
    rows_ContainerId:'#s2baia_container',
    search_InputId:'#s2baia_search',
    load_RowsAction:'s2b_gpt_load_log',
    delete_RowsAction:'s2b_gpt_delete_log',
    bot_Provider:1,
    loader_Selector: null
};



//typeof s2baiaRowsOptions === 'object' && !Array.isArray(s2baiaRowsOptions) && s2baiaRowsOptions !== null


class S2baiaRowsManager {

    constructor(options) {
        this.rowPageId = options.row_PageId;//'#s2baia_page_log';
        this.rowsPerPageId = options.rows_PerPageId;//'#logs_per_page';
        this.rowsContainerId = options.rows_ContainerId;//'#s2baia_container';
        this.searchInputId = options.search_InputId;//'#s2baia_search';
        this.loadRowsAction = options.load_RowsAction;//'s2b_gpt_load_log';
        this.deleteRowsAction = options.delete_RowsAction;//'s2b_gpt_delete_log';
        
        this.rowLoadnonce = options.row_Loadnonce;//variable s2b_gpt_loadnoncec
        this.selectedRowSpan = options.selected_Span;//#s2baia_selected_span_'
        this.selectedRowHref = options.selected_Href;//'#s2baia_selected_href_'
        this.tableElementId = options.table_ElementId;//this is because many table elements can be in one page //'#s2baia_instructions'
        this.searchSubmitElementId = options.search_SubmitElementId;// '#s2baia_search_submit'
        this.messageLogConfirmDelete = options.message_LogConfirmDelete; //s2b_message_log_confirm_delete
        this.rowDellogNonce = options.row_DellogNonce;//s2b_bot_dellognonce
        this.ajaxAction = options.ajax_Action;//s2b_bot_dellognonce
        this.tableRowHrefPrefix = options.table_Row_Href_Prefix;
        this.messageUpdateSuccess = options.message_Update_Success;
        this.messageNewSuccess = options.message_New_Success;
        this.tableContainer = options.table_Container;
        this.pageNumbers = options.page_Numbers;
        this.totalRows = options.total_Rows;
        this.rowItems = options.row_items;
        this.appSuffix = options.app_suffix;
        this.botProvider = options.bot_Provider;
        this.loaderSelector = options.loader_Selector;
        //#s2baia_selected_span_' + modifiedInstructionId);
        //'#s2baia_selected_href_' + modifiedInstructionId);
        
        this.s2bRows = {};
        this.s2baiaRowInfos = {};
        this.s2baiaRowMessages = {};

        this.initialize();
    }

    initialize() {
        document.querySelector(this.rowPageId).value = 0;
    }

    showLoader(loaderSelector) {
        if (!jQuery) {
            return;

        }

        if (typeof loaderSelector === 'string') {
            jQuery(loaderSelector).css('display', 'grid');
        } else {
            if(typeof this.loaderSelector === 'string'){
                jQuery(this.loaderSelector).css('display', 'grid');
            }else{
                jQuery('.s2baia-custom-loader').css('display', 'grid');
            }
        }
    }

    hideLoader() {
        if (jQuery) {
            jQuery('.s2baia-custom-loader').hide();
        }
    }

    toggleSelectedRow(s2bdata) {
        this.performRequestCall(this.toggleSelectedRowCallbacks(), s2bdata);
    }

    loadRows(elementSelector, side, distance,successcallback) {//s2baiaLoadLogs
        const s2bdata = this.collectRowData();
        this.putLoader(elementSelector, side, distance);
        this.performRequestCall(this.loadRowCallbacks(successcallback), s2bdata);
    }

    deleteRow(s2bdata) {
        s2bdata.sendAjax = 1;
        s2bdata.sendJson = 1;
        this.performRequestCall(this.deleteRowCallbacks(), s2bdata);
    }

    collectRowData() {
        return {
            s2b_gpt_loadnonce: this.rowLoadnonce,
            action: this.loadRowsAction,
            provider: this.botProvider,
            rows_per_page: document.querySelector(this.rowsPerPageId).value,
            search: document.querySelector(this.searchInputId).value,
            page: document.querySelector(this.rowPageId).value,
            sendAjax:1
        };
    }

    ajaxSuccessUpdateRow(res) {//Object specific. Override in child classes.
        if (!('result' in res) || res.result != 200) {
            return;
        }
        console.log(res);
        
    }

    putLoader(elementSelector, side, distance, loaderSelector) {
        let targetelement = document.querySelector(elementSelector);// '.s2baia_button_container.s2baia_bloader'
        //let loader = document.querySelector('.s2baia-instructions-loader');
        let loader = null;
        if (loaderSelector) {
            loader = document.querySelector(loaderSelector);
        } else {
            loader = document.querySelector('.s2baia-instructions-loader');
        }
        if (targetelement && loader) {

            let rect = targetelement.getBoundingClientRect();
            let rght = rect.right - rect.left;
            console.log(rect);
            console.log(rght);
            if (rect) {
                if (side === 'left') {
                    loader.style.left = (Math.round(rect.left) - Math.round(distance)) + 'px';
                    loader.style.top = (Math.round(rect.top) - 5) + 'px';
                }
                if (side === 'right') {
                    loader.style.left = (Math.round(rect.right) + Math.round(distance)) + 'px';
                    loader.style.top = (Math.round(rect.top) - 5) + 'px';
                }

            }
            //console.log('left = ' + loader.style.left);
            //console.log('top = ' + loader.style.top);
        }

    }

    performRequestCall(callbacks, data) {
        //callbackFn = callbacks;
        this.showLoader();
        if(typeof data === 'object' && !Array.isArray(data) && data !== null && 'sendAjax' in data && data.sendAjax === 1){
            this.performAjax(callbacks, data);
        }else{
            this.performFetch(callbacks, data);
        }
    }
    
    performAjax(callbacks,data){
        jQuery.ajax({
            url: this.ajaxAction,
            type: 'POST',
            dataType: 'json',
            context: this,
            data: data,
            beforeSend: null,
            success: callbacks.ajaxSuccess,
            complete: callbacks.ajaxComplete
        });
    }
    
    performFetch(callbacks, data){
        let bdy = null;
        if(typeof data === 'object' && !Array.isArray(data) && data !== null && 'sendRaw' in data && data.sendRaw === 1){
            bdy = data;
        }else{
            bdy = JSON.stringify(data);
        }
        let hdrs = {};
        if(typeof data === 'object' && !Array.isArray(data) && data !== null && 'sendJson' in data && data.sendJson === 1){
            hdrs = {'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };
        }else{
            hdrs = {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'Accept': 'application/json, text/javascript, */*; q=0.01'
            };
        }
        fetch(this.ajaxAction, {
            method: 'POST',
            body: bdy,
            headers: hdrs
        })
        .then(response => response.json())
        .then(callbacks.ajaxSuccess)
        .finally(callbacks.ajaxComplete);
    }

    toggleSelectedRowCallbacks() {
        return {
            ajaxBefore: this.showLoader,
            ajaxSuccess: res => this.ajaxSuccessUpdateRow(res),
            ajaxComplete: this.hideLoader
        };
    }

    loadRowCallbacks(successcallback) {
        return {
            ajaxBefore: this.showLoader,
            ajaxSuccess: res => successcallback(res),
            ajaxComplete: this.hideLoader
        };
    }

    deleteRowCallbacks() {
        return {
            ajaxBefore: this.showLoader,
            ajaxSuccess: res => this.ajaxSuccessUpdateRow(res),
            ajaxComplete: this.hideLoader
        };
    }


    loadRowsE(e) {//s2baiaLoadLogsE
        e.preventDefault();
        document.querySelector(this.rowPageId).value = 0;
        let boundajaxSuccessUpdateRow = this.ajaxSuccessUpdateRow.bind(this);
        this.loadRows(this.rowsContainerId,'right', -70,boundajaxSuccessUpdateRow);
    }

    nextRowPage(e) {
        e.preventDefault();
        let currentPage = document.querySelector(this.rowPageId).value;
        document.querySelector(this.rowPageId).value = (+currentPage) + 1;
        this.disablePointerEvents('.s2bnext'+ this.pageNumbers);
        let boundajaxSuccessUpdateRow = this.ajaxSuccessUpdateRow.bind(this);
        this.loadRows(this.rowsContainerId,'left', -70,boundajaxSuccessUpdateRow);
    }

    changeRowPerPage(el) {
        document.querySelector(this.rowPageId).value = 0;
        let boundajaxSuccessUpdateRow = this.ajaxSuccessUpdateRow.bind(this);
        this.loadRows(this.rowsContainerId, 'left', -70,boundajaxSuccessUpdateRow);
    }

    prevRowPage(e) {
        e.preventDefault();
        let currentPage = document.querySelector(this.rowPageId).value;
        document.querySelector(this.rowPageId).value = (+currentPage) - 1;
        this.disablePointerEvents('.s2bprevious'+ this.pageNumbers);
        let boundajaxSuccessUpdateRow = this.ajaxSuccessUpdateRow.bind(this);
        this.loadRows(this.rowsContainerId, 'left', -70,boundajaxSuccessUpdateRow);
    }

    searchRowKeyUp(e) {
        e.preventDefault();
        if (e.key === 'Enter' || e.keyCode === 13) {
            document.querySelector(this.rowPageId).value = 0;
            let boundajaxSuccessUpdateRow = this.ajaxSuccessUpdateRow.bind(this);
            this.loadRows('.s2baia_button_container.s2baia_bloader','left', -70,boundajaxSuccessUpdateRow);
        }
    }

    clearSearch(e) {
        e.preventDefault();
        let search = document.querySelector(this.searchInputId);
        if (search) {
            search.value = '';
            document.querySelector(this.rowPageId).value = 0;
            let boundajaxSuccessUpdateRow = this.ajaxSuccessUpdateRow.bind(this);
            this.loadRows(this.rowsContainerId,'right', -70,boundajaxSuccessUpdateRow);
        }
    }
    
    removeRow(e, row_id) {
        e.preventDefault();
        let wantRemove = confirm(this.messageLogConfirmDelete + ':' + row_id);
        if (!wantRemove) return;

        let s2bdata = {
            s2b_bot_dellognonce: this.rowDellogNonce,
            action: this.deleteRowsAction,
            id: row_id
        };
        document.querySelector(this.rowPageId).value = 1;
        this.putLoader(this.rowsContainerId, 'left', -70);
        this.deleteRow(s2bdata);
    }

    
    
    disablePointerEvents(classes = '') {
        let els = document.querySelectorAll(classes);
        let els_l = els.length;
        for (let i = 0; i < els_l; i++) {
            els[i].style.pointerEvents = 'none';//
        }
    }

    showPagination(data) {//
        
        let total_instructions = data['total'];
        let page = data['page'];
        let items_per_page = data['items_per_page'];
        let prev_page = page - 1;

        let show_next = true;
        if ((page * items_per_page) >= +total_instructions) {
            show_next = false;
        }

        let show_prev = true;
        if (prev_page < 1) {
            show_prev = false;
        }
        let prev_page_as = document.querySelectorAll('.s2bprevious'+ this.pageNumbers);
        let next_page_as = document.querySelectorAll('.s2bnext'+ this.pageNumbers);
        for (let i = 0; i < prev_page_as.length; i++) {
            let prev_page_a = prev_page_as[i];
            prev_page_a.style.pointerEvents = '';//
            if (show_prev) {
                prev_page_a.style.display = 'inline-block';
            } else {
                prev_page_a.style.display = 'none';
            }
        }

        for (let i = 0; i < next_page_as.length; i++) {
            let next_page_a = next_page_as[i];
            next_page_a.style.pointerEvents = '';//
            if (show_next) {

                next_page_a.style.display = 'inline-block';

            } else {
                next_page_a.style.display = 'none';
            }
        }



        let totals = document.querySelectorAll(this.totalRows);
        for (let i = 0; i < totals.length; i++) {
            let total = totals[i];
            total.innerHTML = 'Total: ' + total_instructions + ' items';
        }
        let page_numbers = document.querySelectorAll(this.pageNumbers + '.current');
        for (let i = 0; i < page_numbers.length; i++) {
            let page_number = page_numbers[i];
            page_number.innerHTML = page;
        }
    }

    setTableContainerHeight() {//
        let table_element = document.querySelector(this.tableElementId);
        if (!table_element) {
            return;
        }
        let table_height = table_element.offsetHeight;
        let table_element2 = document.querySelector(this.searchSubmitElementId);
        if (!table_element2) {
            return;
        }
        let search_submit_h = table_element2.offsetHeight;
        let pagination = document.querySelector('.s2baia_pagination');
        let pl = 0;
        if (pagination) {
            pl = pagination.offsetHeight;
        }
        let container_height = table_height + search_submit_h + pl + 20;

        return container_height;
    }

    removeTableRow(del_row){//remove table row by  having link in row <a id=''
        let link_to_delete = document.querySelector("#"+this.tableRowHrefPrefix + del_row);
        if (link_to_delete) {
            link_to_delete.parentElement.parentElement.remove();//remove tr
        }
    }
    
    displayAlert(msg){
        alert(msg);
    }

}

class S2baiaStyledBotManager extends S2baiaRowsManager{
    constructor(options) {
        super(options);
    }
    
    selectedStyledBot(e,bot_id,itmsuffix){
        e.preventDefault();
        let str_bot_id = bot_id + '';
        let s2baia_bots = this.rowItems;
        document.querySelector('#s2baia_chatbot_position'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['position'];
        document.querySelector('#s2baia_chatbot_icon_position'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['icon_position'];
        document.querySelector('#s2baia_chatbot_chat_icon_size'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['chat_icon_size'];

        document.querySelector('#s2baia_chatbot_chat_width'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['chat_width'];
        document.querySelector('#s2baia_chatbot_width_metrics'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['chat_width_metrics'];
        document.querySelector('#s2baia_chatbot_chat_height'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['chat_height'];

        document.querySelector('#s2baia_chatbot_height_metrics'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['chat_height_metrics'];
        document.querySelector('#s2baia_chatbot_chatbot_picture_url'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['chatbot_picture_url'];
        document.querySelector('#s2baia_chatbot_send_text_color'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['send_button_text_color'];

        
        document.querySelector('#s2baia_chatbot_message_font_size'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['message_font_size'];

        document.querySelector('#s2baia_chatbot_message_margin'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['message_margin'];
        document.querySelector('#s2baia_chatbot_message_border_radius'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['message_border_radius'];
        document.querySelector('#s2baia_chatbot_chatbot_border_radius'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['chatbot_border_radius'];

        document.querySelector('#s2baia_chatbot_header_text_color'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['header_text_color'];
        document.querySelector('#s2baia_chatbot_header_color'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['header_color'];
        document.querySelector('#s2baia_chatbot_send_button_color'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['send_button_color'];

        document.querySelector('#s2baia_chatbot_send_button_hover_color'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['send_button_hover_color'];
        //document.querySelector('#s2baia_chatbot_send_text_color').value = s2baia_bots[str_bot_id]['bot_options']['send_button_text_color'];
        document.querySelector('#s2baia_chatbot_message_bg_color2'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['message_bg_color'];

        document.querySelector('#s2baia_chatbot_message_text_color2'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['message_text_color'];
        document.querySelector('#s2baia_chatbot_response_bg_color2'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['response_bg_color'];
        document.querySelector('#s2baia_chatbot_response_text_color2'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['response_text_color'];
        document.querySelector('#s2baia_chatbot_response_icons_color2'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['response_icons_color'];
        
        document.querySelector('#s2baia_chatbot_html_id_closed_bot'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['html_id_closed_bot'];
        document.querySelector('#s2baia_chatbot_html_id_open_bot'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['html_id_open_bot'];
        document.querySelector('#s2baia_chatbot_custom_css'+itmsuffix).value = s2baia_bots[str_bot_id]['bot_options']['custom_css'];
        
    }
    
}

class S2baiaChatGptManager extends S2baiaStyledBotManager{
    
    constructor(options) {
        super(options);
        this.botTogglenonce = options.bot_Togglenonce;//variable s2b_gpt_loadnoncec
        this.msgJqueryNotInstalled = options.msg_JqueryNotInstalled;//
        this.botEditedBot = options.bot_EdidtedBot;//s2baia_edited_instruction_id
    }
    
    editBot(e, bot_id,itmsuffix){
        let s2baia_bots = this.rowItems;
        e.preventDefault();
        let str_bot_id = bot_id + '';
        this.botEditedBot = bot_id;
        document.querySelector('#s2baia_idbot'+itmsuffix).value = bot_id;//
        console.log(str_bot_id);
        if (str_bot_id in s2baia_bots && 'bot_options' in s2baia_bots[str_bot_id]) {
            let bot_options = s2baia_bots[str_bot_id]['bot_options'];
            let context = bot_options['context'];
            console.log(context);
            document.querySelector('#s2baia_chatbot_context'+itmsuffix).value = context;
            document.querySelector('#s2baia_chatbot_chat_model'+itmsuffix).value = bot_options['model'];
            document.querySelector('#s2baia_chatbot_chat_temperature'+itmsuffix).value = bot_options['chat_temperature'];
            document.querySelector('#s2baia_chatbot_chat_top_p'+itmsuffix).value = bot_options['chat_top_p'];
            document.querySelector('#s2baia_chatbot_max_tokens'+itmsuffix).value = bot_options['max_tokens'];
            document.querySelector('#s2baia_chatbot_frequency_penalty'+itmsuffix).value = bot_options['frequency_penalty'];
            
            document.querySelector('#s2baia_chatbot_presence_penalty'+itmsuffix).value = bot_options['presence_penalty'];
            document.querySelector('#s2b_chatbot_hash'+itmsuffix).value = s2baia_bots[str_bot_id]['hash_code'];
            document.querySelector('#s2baia_chatbot_send_button_text'+itmsuffix).value = bot_options['send_button_text'];

            document.querySelector('#s2baia_chatbot_clear_button_text'+itmsuffix).value = bot_options['clear_button_text'];
            document.querySelector('#s2baia_chatbot_message_placeholder'+itmsuffix).value = bot_options['message_placeholder'];
            document.querySelector('#s2baia_chatbot_chatbot_name'+itmsuffix).value = bot_options['chatbot_name'];
            document.querySelector('#s2baia_chatbot_compliance_text'+itmsuffix).value = bot_options['compliance_text'];
            
            document.querySelector('#s2baia_shortcode').innerHTML = '[s2baia_chatbot bot_id="'+s2baia_bots[str_bot_id]['hash_code']+'"]';
            let s2baia_selected_bot_info = document.querySelectorAll('.s2baia_selected_bot_info');
            Array.from(s2baia_selected_bot_info).forEach((s2belement, index) => {
                s2belement.innerHTML = ' You selected: '+s2baia_bots[str_bot_id]['hash_code']+' ID: '+s2baia_bots[str_bot_id]['id'];
            });
            let s2baccessguest = document.querySelector('#s2baia_access_for_guests'+itmsuffix);
            
            if(s2baccessguest){
                if(bot_options['access_for_guests'] == 1){
                    s2baccessguest.checked = true;//setAttribute("checked", "");
                }else{
                    s2baccessguest.checked = false;//.setAttribute("checked", "");
                }
            }
            let buttons = {'s2baia_submit_edit_instruction': [1, 1, 'Save'], 's2baia_saveasnew_instruction': [1, 1, ''],
                's2baia_new_instruction': [1, 1, ''], 's2baia_remove_instruction': [1, 1, '']};
            this.toggleButtons(buttons);
            this.selectedStyledBot(e, bot_id,itmsuffix);
            
        }
    }
    
    newBot(e,options,itmsuffix) {
        e.preventDefault();
        document.querySelector('#s2baia_idbot' + itmsuffix).value = '0';
        document.querySelector('#s2b_chatbot_hash' + itmsuffix).value = 'generatenew';
        document.querySelector('#s2baia_chatbot_position' + itmsuffix).value = options.position;
        document.querySelector('#s2baia_chatbot_icon_position' + itmsuffix).value = options.icon_position;
        document.querySelector('#s2baia_chatbot_width_metrics' + itmsuffix).value = options.chat_width_metrics;
        document.querySelector('#s2baia_chatbot_height_metrics' + itmsuffix).value = options.chat_height_metrics;
        document.querySelector('#s2baia_chatbot_chat_model' + itmsuffix).value = options.model;
        document.querySelector('#s2baia_chatbot_context' + itmsuffix).value = options.context;
        document.querySelector('#s2baia_chatbot_chat_icon_size' + itmsuffix).value = options.chat_icon_size;
        document.querySelector('#s2baia_chatbot_chat_width' + itmsuffix).value = options.chat_width;
        document.querySelector('#s2baia_chatbot_chat_height' + itmsuffix).value = options.chat_height;
        document.querySelector('#s2baia_chatbot_chatbot_picture_url' + itmsuffix).value = options.chatbot_picture_url;
        document.querySelector('#s2baia_chatbot_send_button_text' + itmsuffix).value = options.send_button_text;
        document.querySelector('#s2baia_chatbot_clear_button_text' + itmsuffix).value = options.clear_button_text;
        document.querySelector('#s2baia_chatbot_message_placeholder' + itmsuffix).value = options.message_placeholder;
        document.querySelector('#s2baia_chatbot_chatbot_name' + itmsuffix).value = options.chatbot_name;
        document.querySelector('#s2baia_chatbot_compliance_text' + itmsuffix).value = options.compliance_text;
        document.querySelector('#s2baia_access_for_guests' + itmsuffix).checked = options.access_for_guests == 1;
        document.querySelector('#s2baia_chatbot_chat_temperature' + itmsuffix).value = options.chat_temperature;
        document.querySelector('#s2baia_chatbot_chat_top_p' + itmsuffix).value = options.chat_top_p;
        document.querySelector('#s2baia_chatbot_max_tokens' + itmsuffix).value = options.max_tokens;
        document.querySelector('#s2baia_chatbot_frequency_penalty' + itmsuffix).value = options.frequency_penalty;
        document.querySelector('#s2baia_chatbot_presence_penalty' + itmsuffix).value = options.presence_penalty;
        document.querySelector('#s2baia_chatbot_header_text_color' + itmsuffix).value = options.header_text_color;
        document.querySelector('#s2baia_chatbot_header_color' + itmsuffix).value = options.header_color;
        document.querySelector('#s2baia_chatbot_send_button_color' + itmsuffix).value = options.send_button_color;
        document.querySelector('#s2baia_chatbot_send_button_hover_color' + itmsuffix).value = options.send_button_hover_color;
        document.querySelector('#s2baia_chatbot_send_text_color' + itmsuffix).value = options.send_button_text_color;
        document.querySelector('#s2baia_chatbot_message_bg_color2' + itmsuffix).value = options.message_bg_color;
        document.querySelector('#s2baia_chatbot_message_text_color2' + itmsuffix).value = options.message_text_color;
        document.querySelector('#s2baia_chatbot_response_bg_color2' + itmsuffix).value = options.response_bg_color;
        document.querySelector('#s2baia_chatbot_response_text_color2' + itmsuffix).value = options.response_text_color;
        document.querySelector('#s2baia_chatbot_response_icons_color2' + itmsuffix).value = options.response_icons_color;
        document.querySelector('#s2baia_chatbot_message_font_size' + itmsuffix).value = options.message_font_size;
        document.querySelector('#s2baia_chatbot_message_margin' + itmsuffix).value = options.message_margin;
        document.querySelector('#s2baia_chatbot_message_border_radius' + itmsuffix).value = options.message_border_radius;
        document.querySelector('#s2baia_chatbot_chatbot_border_radius' + itmsuffix).value = options.chatbot_border_radius;
        
        if('html_id_closed_bot' in options ){
            document.querySelector('#s2baia_chatbot_html_id_closed_bot' + itmsuffix).value = options.html_id_closed_bot;
        }else{
            document.querySelector('#s2baia_chatbot_html_id_closed_bot' + itmsuffix).value = '';
        }
        
        if('html_id_open_bot' in options ){
            document.querySelector('#s2baia_chatbot_html_id_open_bot' + itmsuffix).value = options.html_id_open_bot;
        }else{
            document.querySelector('#s2baia_chatbot_html_id_open_bot' + itmsuffix).value  = '';
        }
        
        if('custom_css' in options ){
            document.querySelector('#s2baia_chatbot_custom_css' + itmsuffix).value = options.custom_css;
        }else{
            document.querySelector('#s2baia_chatbot_custom_css' + itmsuffix).value  = '';
        }
        
        
        
        let s2baia_selected_bot_info = document.querySelectorAll('.s2baia_selected_bot_info');						
	Array.from(s2baia_selected_bot_info).forEach((s2belement, index) => {
                s2belement.innerHTML = ' You are going to create new bot ';
        });
        
        let buttons = {'s2baia_submit_edit_instruction': [1, 1, 'Save'], 's2baia_saveasnew_instruction': [1, 1, ''],
                's2baia_new_instruction': [1, 1, ''], 's2baia_remove_instruction': [1, 1, '']};
        this.toggleButtons(buttons);
    }

    
    toggleButtons(buttons) {
        for (let bid in buttons) {
            let btn = document.querySelector('#' + bid);
            if (!btn) {
                continue;
            }
            if (buttons[bid][0] === 1) {
                btn.style.display = 'inline-block';
            } else {
                btn.style.display = 'none';
            }
            if (buttons[bid][1] === 1) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
            let btncaption = buttons[bid][2];
            if (btncaption.length > 0) {
                btn.innerHTML = btncaption;
            }
        }
    }
    

    saveChatbot(e) {
        e.preventDefault();

        if(!jQuery){
            this.displayAlert(this.msgJqueryNotInstalled);
            return;
        }
        let genForm = jQuery('#s2baia_chatbot_edit_form');
        let data = genForm.serialize();
        console.log(data);
        this.putLoader(this.rowsContainerId, 'left', -70);
        this.performRequestCall(this.saveBotCallbacks(), data);
        //s2b_performAjax.call(s2b_chatbot_general_tab_dynamic, data);

    }
    
    saveBotCallbacks() {
        return {
            ajaxBefore: this.showLoader,
            ajaxSuccess: res => this.ajaxSuccessUpdateBotRow(res),
            ajaxComplete: this.hideLoader
        };
    }
    
    ajaxSuccessUpdateBotRow(res){
        console.log('ajaxSuccessUpdateBotRow');
        console.log(res);
        if (!('result' in res) ) {
            return;
        }
        
        //check if was created new bot. If yes then we need update appropriate hidden fields
        //and after this we need call this.loadRows('.s2baia_bloader.s2baia_gbutton_container', 'left', -70,this.ajaxSuccessUpdateBot);
        let new_bot = false;
        
        let bot_hash = document.querySelector('#s2b_chatbot_hash'+this.appSuffix).value;
        if(bot_hash === 'generatenew'){
            new_bot = true;
        }
        //set new hash
        if(!('bot' in res)){
            return;
        }
        if(!('id' in res.bot)){
            return;
        }
        if(!('hash_code' in res.bot)){
            return;
        }
        let id_bot = res.bot.id;
        let bothash_code = res.bot.hash_code;
        document.querySelector('#s2baia_idbot'+this.appSuffix).value = id_bot;
        document.querySelector('#s2b_chatbot_hash'+this.appSuffix).value = bothash_code;
        //set new id
        
        if(new_bot){
            document.querySelector(this.searchInputId).value = '';//set search field t empty string
            document.querySelector(this.rowPageId).value = 1;//set page 1
            let boundAjaxSuccessUpdate = this.ajaxSuccessUpdateBot.bind(this);
            let s2baia_selected_bot_info = document.querySelectorAll('.s2baia_selected_bot_info')
            Array.from(s2baia_selected_bot_info).forEach((s2belement, index) => {
                s2belement.innerHTML = ' ';
            });
            this.displayAlert(this.messageNewSuccess);
            this.loadRows('.s2baia_bloader.s2baia_gbutton_container', 'left', -70,boundAjaxSuccessUpdate);
            return;
        }
        
        this.updateBotLine(res);
        
        this.displayAlert(this.messageUpdateSuccess);
        
    }
    
    updateBotLine(res){
        
        let id_bot = res.bot.id;
        let bothash_code = res.bot.hash_code;
        
        if(!('bot_options' in res.bot)){
            return;
        }
        let botoptions = res.bot.bot_options;
        if(!('chatbot_name' in botoptions)){
            return;
        }
        if(!('model' in botoptions)){
            return;
        }
        if(!('position' in botoptions)){
            return;
        }
        
        let s2baia_bot_href = document.querySelector('#s2baia_bot_href_' + id_bot);
        if (!s2baia_bot_href) {
            return;
        }
        s2baia_bot_href.innerHTML = bothash_code;
        
        let s2baia_model_span = document.querySelector('#s2baia_model_span_' + id_bot);
        if (!s2baia_model_span) {
            return;
        }
        s2baia_model_span.innerHTML = botoptions.model;
        
        let s2baia_chatbotname_span = document.querySelector('#s2baia_chatbotname_span_' + id_bot);
        if (!s2baia_chatbotname_span) {
            return;
        }
        s2baia_chatbotname_span.innerHTML = botoptions.chatbot_name;
        
        let s2baia_position_span = document.querySelector('#s2baia_position_span_' + id_bot);
        if (!s2baia_position_span) {
            return;
        }
        s2baia_position_span.innerHTML = botoptions.position;
        
        document.querySelector('#s2baia_shortcode'+this.appSuffix).innerHTML = '[s2baia_chatbot bot_id="'+res.bot.hash_code+'"]';
        this.rowItems[id_bot].bot_options = botoptions;
    }
    
    deleteRowCallbacks() {
        return {
            ajaxBefore: this.showLoader,
            ajaxSuccess: res => this.ajaxSuccessDeleteRow(res),
            ajaxComplete: this.hideLoader
        };
    }
    
    ajaxSuccessDeleteRow(res){
        console.log('ajaxSuccessDeleteRow');
        console.log(res);
        if (!'result' in res ) {
            return;
        }
        
        if(res.result != 200){
            if('msg' in res){
                this.displayAlert(res.msg );
            }
            return;
        }
        
        let del_row = res.del_row;

        this.removeTableRow(del_row);
        
        //edit parts start
        delete s2baia_instructions[del_row];//remove row from array
        if (+s2baia_edited_instruction_id === +del_row) {//if deleted instruction equals edited instruction
            s2baia_edited_instruction_id = 0;
        }

        let s2baia_idinstruction = document.querySelector('#s2baia_idbot'+this.appSuffix).value;
        if (+del_row === +s2baia_idinstruction && false) {
            
            let buttons = {'s2baia_submit_edit_instruction': [1, 0, 'Add'], 's2baia_saveasnew_instruction': [0, 1, ''],
                's2baia_new_instruction': [0, 1, ''], 's2baia_remove_instruction': [0, 1, '']};
            s2baiaToggleButtons(buttons);
        }
        //edit parts end
        let boundAjaxSuccessUpdate = this.ajaxSuccessUpdateBot.bind(this);
        this.loadRows('.s2baia_bloader.s2baia_gbutton_container', 'left', -70,boundAjaxSuccessUpdate);

        
        
    }
    
    ajaxSuccessUpdateRow(res) {//
        this.ajaxSuccessUpdateBot(res); 
    }
    
    ajaxSuccessUpdateBot(res){//redraw rows table
        console.log('ajaxSuccessUpdateBot');
        console.log(res);
        if (!('result' in res) || res.result != 200) {
            if('msg' in res){
                this.displayAlert(res.msg);
            }
            return;
        }
        
        if(!('chat_bots' in res )){
            return;
        }
        
        
        this.rowItems = res.chat_bots;
        let pagidata = {'page': res.page, 'items_per_page': res.rows_per_page, 'total': res.total};
        //s2baia_edited_instruction_id = 0;
        let tbody = document.querySelector('#s2baia-bots-list'+this.appSuffix);
        if (!tbody) {
            return 0;
        }
        tbody.innerHTML = '';
        let rows = '';
        for (let idx in this.rowItems) {
            let bot_o = this.rowItems[idx];
            let bot_options = bot_o.bot_options;//JSON.parse();
            console.log(bot_options);
            //JSON.parse()
            let tr = '<tr class="';
            if (bot_o.disabled === '1') {
                tr += 's2baia_disabled_text';
            }
            tr += '">';
            let td1 = '<td class="id_column">' + bot_o.id + '</td>';
            let td2 = '<td><a href="#" onclick="s2b_chatbot_list.editBot(event,' + bot_o.id + ",'"+this.appSuffix+'\' )" id="s2baia_bot_href_' + bot_o.id + '">' + bot_o.hash_code + '</a></td>';
            
            let td3 = '<td class="mvertical"><span id="s2baia_model_span_' + bot_o.id + '">' + bot_options.model + '</span></td>';
            
            let td4 = '<td class=""><span id="s2baia_chatbotname_span_' + bot_o.id + '">' + bot_options.chatbot_name + '</span></td>';
            let td5 = '<td class="s2baia_user mvertical"><span id="s2baia_position_span_'+bot_o.id+'">' + bot_options.position + '</span></td>';
            
            
            let td6 = '<td class="s2baia_flags_td"><span title="edit" class="dashicons dashicons-edit" onclick="s2b_chatbot_list.editBot(event,' + bot_o.id + ",'"+this.appSuffix+'\' )"></span> ';
            
            td6 += '<span title="remove" class="dashicons dashicons-trash"  onclick="s2b_chatbot_list.removeRow(event,' + bot_o.id + ')"></span></td>';
            tr = tr + td1 + td2 + td3 + td4 + td5 + td6 + '</tr>';
            rows = rows + tr;
        }
        tbody.innerHTML = rows;

        this.showPagination(pagidata);
        let totals = document.querySelectorAll(this.totalRows);
        for (let i = 0; i < totals.length; i++) {
            let total = totals[i];
            total.innerHTML = 'Total: ' + res.total + ' items';
        }

        let new_table_container_height = this.setTableContainerHeight();//
        if (new_table_container_height > s2baia_instruction_table_height) {
            s2baia_instruction_table_height = new_table_container_height;
            let  tbl_div = document.querySelector(this.tableContainer);
            if (tbl_div) {
                tbl_div.style.height = s2baia_instruction_table_height + 'px';
            }
        }

        
    }
}

class S2baiaAssistantManager extends S2baiaStyledBotManager{
    
    constructor(options) {
        super(options);
        this.botTogglenonce = options.bot_Togglenonce;//variable s2b_gpt_loadnoncec
        this.msgJqueryNotInstalled = options.msg_JqueryNotInstalled;//
        this.botEditedBot = options.bot_EdidtedBot;//s2baia_edited_instruction_id
    }
    
    editBot(e, bot_id,itmsuffix){
        
        let s2baia_bots = this.rowItems;
        e.preventDefault();
        let str_bot_id = bot_id + '';
        this.botEditedBot = bot_id;
        document.querySelector('#s2baia_idbot'+itmsuffix).value = bot_id;//
        console.log(str_bot_id);
        
        if (str_bot_id in s2baia_bots && 'bot_options' in s2baia_bots[str_bot_id]) {
            let bot_options = s2baia_bots[str_bot_id]['bot_options'];
            console.log(bot_options);
            let context = bot_options['context'];
            console.log(context);
            //document.querySelector('#s2baia_chatbot_instruction'+itmsuffix).value = context;
            //document.querySelector('#s2baia_chatbot_chat_model'+itmsuffix).value = bot_options['model'];
            
            document.querySelector('#s2b_chatbot_hash'+itmsuffix).value = s2baia_bots[str_bot_id]['hash_code'];
            document.querySelector('#s2baia_chatbot_send_button_text'+itmsuffix).value = bot_options['send_button_text'];

            document.querySelector('#s2baia_chatbot_clear_button_text'+itmsuffix).value = bot_options['clear_button_text'];
            document.querySelector('#s2baia_chatbot_message_placeholder'+itmsuffix).value = bot_options['message_placeholder'];
            document.querySelector('#s2baia_chatbot_chatbot_name'+itmsuffix).value = bot_options['chatbot_name'];
            document.querySelector('#s2baia_chatbot_compliance_text'+itmsuffix).value = bot_options['compliance_text'];
            let tmout = parseInt(bot_options['assistant_timeout'], 10);
            if(tmout < 1 || Number.isNaN(tmout)){
                tmout = 7;
            }
            document.querySelector('#s2baia_assistant_timeout'+itmsuffix).value = tmout;
            
            if('assistant_id' in bot_options && bot_options['assistant_id'].length > 0){
                console.log(bot_options['assistant_id']);
                document.querySelector('#s2baia_assistant_id'+itmsuffix).value = bot_options['assistant_id'];
            }else{
                console.log(bot_options['id']);
                document.querySelector('#s2baia_assistant_id'+itmsuffix).value = bot_options['id'];
            }
            
            let shortcodecont = document.querySelector('.s2baia_instruction'+itmsuffix);
            if(shortcodecont){
                document.querySelector('#s2baia_shortcode'+itmsuffix).innerHTML = '[s2baia_chatbot bot_id="'+s2baia_bots[str_bot_id]['hash_code']+'"]';
                shortcodecont.style.display = 'block';
                shortcodecont.style.color = '#ae1000';
            }
            let s2baia_selected_bot_info = document.querySelectorAll('.s2baia_selected_bot_info'+itmsuffix);
            Array.from(s2baia_selected_bot_info).forEach((s2belement, index) => {
                s2belement.innerHTML = ' You selected: '+s2baia_bots[str_bot_id]['hash_code']+' ID: '+s2baia_bots[str_bot_id]['id'];
            });
            let s2baccessguest = document.querySelector('#s2baia_access_for_guests'+itmsuffix);
            
            if(s2baccessguest){
                if(bot_options['access_for_guests'] == 1){
                    s2baccessguest.checked = true;//setAttribute("checked", "");
                }else{
                    s2baccessguest.checked = false;//.setAttribute("checked", "");
                }
            }

            this.selectedStyledBot(e, bot_id,itmsuffix);
            
        }
    }
    
    newBot(e,options,itmsuffix) {
        e.preventDefault();
        document.querySelector('#s2baia_idbot' + itmsuffix).value = '0';
        document.querySelector('#s2b_chatbot_hash' + itmsuffix).value = 'generatenew';
        document.querySelector('#s2baia_chatbot_position' + itmsuffix).value = options.position;
        document.querySelector('#s2baia_chatbot_icon_position' + itmsuffix).value = options.icon_position;
        document.querySelector('#s2baia_chatbot_width_metrics' + itmsuffix).value = options.chat_width_metrics;
        document.querySelector('#s2baia_chatbot_height_metrics' + itmsuffix).value = options.chat_height_metrics;
        
        document.querySelector('#s2baia_chatbot_chat_icon_size' + itmsuffix).value = options.chat_icon_size;
        document.querySelector('#s2baia_chatbot_chat_width' + itmsuffix).value = options.chat_width;
        document.querySelector('#s2baia_chatbot_chat_height' + itmsuffix).value = options.chat_height;
        document.querySelector('#s2baia_chatbot_chatbot_picture_url' + itmsuffix).value = options.chatbot_picture_url;
        document.querySelector('#s2baia_chatbot_send_button_text' + itmsuffix).value = options.send_button_text;
        document.querySelector('#s2baia_chatbot_clear_button_text' + itmsuffix).value = options.clear_button_text;
        document.querySelector('#s2baia_chatbot_message_placeholder' + itmsuffix).value = options.message_placeholder;
        document.querySelector('#s2baia_chatbot_chatbot_name' + itmsuffix).value = options.chatbot_name;
        document.querySelector('#s2baia_chatbot_compliance_text' + itmsuffix).value = options.compliance_text;
        let tmout = parseInt(options.assistant_timeout, 10);
            if(tmout < 1 || Number.isNaN(tmout)){
                tmout = 7;
            }
        document.querySelector('#s2baia_assistant_timeout'+itmsuffix).value = tmout;
        document.querySelector('#s2baia_access_for_guests' + itmsuffix).checked = options.access_for_guests == 1;
        document.querySelector('#s2baia_chatbot_header_text_color' + itmsuffix).value = options.header_text_color;
        document.querySelector('#s2baia_chatbot_header_color' + itmsuffix).value = options.header_color;
        document.querySelector('#s2baia_chatbot_send_button_color' + itmsuffix).value = options.send_button_color;
        document.querySelector('#s2baia_chatbot_send_button_hover_color' + itmsuffix).value = options.send_button_hover_color;
        document.querySelector('#s2baia_chatbot_send_text_color' + itmsuffix).value = options.send_button_text_color;
        document.querySelector('#s2baia_chatbot_message_bg_color2' + itmsuffix).value = options.message_bg_color;
        document.querySelector('#s2baia_chatbot_message_text_color2' + itmsuffix).value = options.message_text_color;
        document.querySelector('#s2baia_chatbot_response_bg_color2' + itmsuffix).value = options.response_bg_color;
        document.querySelector('#s2baia_chatbot_response_text_color2' + itmsuffix).value = options.response_text_color;
        document.querySelector('#s2baia_chatbot_response_icons_color2' + itmsuffix).value = options.response_icons_color;
        document.querySelector('#s2baia_chatbot_message_font_size' + itmsuffix).value = options.message_font_size;
        document.querySelector('#s2baia_chatbot_message_margin' + itmsuffix).value = options.message_margin;
        document.querySelector('#s2baia_chatbot_message_border_radius' + itmsuffix).value = options.message_border_radius;
        document.querySelector('#s2baia_chatbot_chatbot_border_radius' + itmsuffix).value = options.chatbot_border_radius;
        document.querySelector('#s2baia_assistant_id'+itmsuffix).value = '';
        
        if('html_id_closed_bot' in options ){
            document.querySelector('#s2baia_chatbot_html_id_closed_bot' + itmsuffix).value = options.html_id_closed_bot;
        }else{
            document.querySelector('#s2baia_chatbot_html_id_closed_bot' + itmsuffix).value = '';
        }
        
        if('html_id_open_bot' in options ){
            document.querySelector('#s2baia_chatbot_html_id_open_bot' + itmsuffix).value = options.html_id_open_bot;
        }else{
            document.querySelector('#s2baia_chatbot_html_id_open_bot' + itmsuffix).value  = '';
        }
        
        if('custom_css' in options ){
            document.querySelector('#s2baia_chatbot_custom_css' + itmsuffix).value = options.custom_css;
        }else{
            document.querySelector('#s2baia_chatbot_custom_css' + itmsuffix).value  = '';
        }
        
        
        
        
        let s2baia_selected_bot_info = document.querySelectorAll('.s2baia_selected_bot_info'+itmsuffix);						
	Array.from(s2baia_selected_bot_info).forEach((s2belement, index) => {
                s2belement.innerHTML = ' You are going to create new bot ';
        });
        

    }


    saveChatbot(e) {
        e.preventDefault();

        if(!jQuery){
            this.displayAlert(this.msgJqueryNotInstalled);
            return;
        }
        let genForm = jQuery('#s2baia_chatbot_edit_form'+this.appSuffix);
        let data = genForm.serialize();
        console.log(data);
        this.putLoader(this.rowsContainerId, 'left', -70);
        this.performRequestCall(this.saveBotCallbacks(), data);
        //s2b_performAjax.call(s2b_chatbot_general_tab_dynamic, data);

    }
    
    saveBotCallbacks() {
        return {
            ajaxBefore: this.showLoader,
            ajaxSuccess: res => this.ajaxSuccessUpdateBotRow(res),
            ajaxComplete: this.hideLoader
        };
    }
    
    ajaxSuccessUpdateBotRow(res){
        console.log('ajaxSuccessDeleteRow');
        console.log(res);
        if (!('result' in res) ) {
            return;
        }
        
        //check if was created new bot. If yes then we need update appropriate hidden fields
        //and after this we need call this.loadRows('.s2baia_bloader.s2baia_gbutton_container', 'left', -70,this.ajaxSuccessUpdateBot);
        let new_bot = false;
        
        let bot_hash = document.querySelector('#s2b_chatbot_hash'+this.appSuffix).value;
        if(bot_hash === 'generatenew'){
            new_bot = true;
        }
        //set new hash
        if(!('bot' in res)){
            return;
        }
        if(!('id' in res.bot)){
            return;
        }
        if(!('hash_code' in res.bot)){
            return;
        }
        let id_bot = res.bot.id;
        let bothash_code = res.bot.hash_code;
        document.querySelector('#s2baia_idbot'+this.appSuffix).value = id_bot;
        document.querySelector('#s2b_chatbot_hash'+this.appSuffix).value = bothash_code;
        //set new id
        
        if(new_bot){
            document.querySelector(this.searchInputId).value = '';//set search field t empty string
            document.querySelector(this.rowPageId).value = 1;//set page 1
            let boundAjaxSuccessUpdate = this.ajaxSuccessUpdateBot.bind(this);
            let s2baia_selected_bot_info = document.querySelectorAll('.s2baia_selected_bot_info'+this.appSuffix)
            Array.from(s2baia_selected_bot_info).forEach((s2belement, index) => {
                s2belement.innerHTML = ' ';
            });
            this.displayAlert(this.messageNewSuccess);
            this.loadRows('.s2baia_bloader.s2baia_gbutton_container', 'left', -70,boundAjaxSuccessUpdate);
            return;
        }
        
        this.updateBotLine(res);
        
        this.displayAlert(this.messageUpdateSuccess);
        
    }
    
    updateBotLine(res){
        
        let id_bot = res.bot.id;
        let bothash_code = res.bot.hash_code;
        
        if(!('bot_options' in res.bot)){
            return;
        }
        let botoptions = res.bot.bot_options;
        if(!('chatbot_name' in botoptions)){
            return;
        }
        if(!('id' in botoptions)){
            return;
        }
        if(!('position' in botoptions)){
            return;
        }
        
        let s2baia_bot_href = document.querySelector('#s2baia_bot_href_' + id_bot);
        if (!s2baia_bot_href) {
            return;
        }
        s2baia_bot_href.innerHTML = bothash_code;
        
        let s2baia_model_span = document.querySelector('#s2baia_model_span_' + id_bot);
        if (!s2baia_model_span) {
            return;
        }
        s2baia_model_span.innerHTML = botoptions.id;
        
        let s2baia_chatbotname_span = document.querySelector('#s2baia_chatbotname_span_' + id_bot);
        if (!s2baia_chatbotname_span) {
            return;
        }
        s2baia_chatbotname_span.innerHTML = botoptions.chatbot_name;
        
        let s2baia_position_span = document.querySelector('#s2baia_position_span_' + id_bot);
        if (!s2baia_position_span) {
            return;
        }
        s2baia_position_span.innerHTML = botoptions.position;
        
        document.querySelector('#s2baia_shortcode'+this.appSuffix).innerHTML = '[s2baia_chatbot bot_id="'+res.bot.hash_code+'"]';
        //console.log(s2baia_assistants);
        console.log(this.rowItems);
        //s2baia_assistants[id_bot].bot_options = botoptions;
        this.rowItems[id_bot].bot_options = botoptions;
    }
    
    deleteRowCallbacks() {
        return {
            ajaxBefore: this.showLoader,
            ajaxSuccess: res => this.ajaxSuccessDeleteRow(res),
            ajaxComplete: this.hideLoader
        };
    }
    
    ajaxSuccessDeleteRow(res){
        console.log('ajaxSuccessDeleteRow');
        console.log(res);
        if (!'result' in res ) {
            return;
        }
        
        if(res.result != 200){
            if('msg' in res){
                this.displayAlert(res.msg );
            }
            return;
        }
        
        let del_row = res.del_row;

        this.removeTableRow(del_row);
        
        //edit parts start
        delete s2baia_instructions[del_row];//remove row from array
        if (+s2baia_edited_instruction_id === +del_row) {//if deleted instruction equals edited instruction
            s2baia_edited_instruction_id = 0;
        }

        let s2baia_idinstruction = document.querySelector('#s2baia_idbot' + this.appSuffix).value;
        if (+del_row === +s2baia_idinstruction && false) {
            

        }
        //edit parts end
        let boundAjaxSuccessUpdate = this.ajaxSuccessUpdateBot.bind(this);
        this.loadRows('.s2baia_bloader.s2baia_gbutton_container', 'left', -70,boundAjaxSuccessUpdate);

        
        
    }
    
    ajaxSuccessUpdateRow(res) {//
        this.ajaxSuccessUpdateBot(res); 
    }
    
    ajaxSuccessUpdateBot(res){//redraw rows table
        console.log('ajaxSuccessUpdateBot');
        console.log(res);
        if (!('result' in res) || res.result != 200) {
            if('msg' in res){
                this.displayAlert(res.msg);
            }
            return;
        }
        
        if(!('chat_bots' in res )){
            return;
        }
        
        
        this.rowItems = res.chat_bots;
        let pagidata = {'page': res.page, 'items_per_page': res.rows_per_page, 'total': res.total};
        //s2baia_edited_instruction_id = 0;
        let tbody = document.querySelector('#s2baia-bots-list'+this.appSuffix);
        if (!tbody) {
            return 0;
        }
        tbody.innerHTML = '';
        let rows = '';
        for (let idx in this.rowItems) {
            let bot_o = this.rowItems[idx];
            let bot_options = bot_o.bot_options;//JSON.parse();
            console.log(bot_options);
            //JSON.parse()
            let tr = '<tr class="';
            if (bot_o.disabled === '1') {
                tr += 's2baia_disabled_text';
            }
            tr += '">';
            let td1 = '<td class="id_column">' + bot_o.id + '</td>';
            let td2 = '<td><a href="#" onclick="s2b_assistants_list.editBot(event,' + bot_o.id + ",'"+this.appSuffix+'\' )" id="s2baia_bot_href_' + bot_o.id + '">' + bot_o.hash_code + '</a></td>';
            
            let td3 = '<td class="mvertical"><span id="s2baia_model_span_' + bot_o.id + '">' + bot_options.id + '</span></td>';
            
            let td4 = '<td class=""><span id="s2baia_chatbotname_span_' + bot_o.id + '">' + bot_options.chatbot_name + '</span></td>';
            let td5 = '<td class="s2baia_user mvertical"><span id="s2baia_position_span_'+bot_o.id+'">' + bot_options.position + '</span></td>';
            
            
            let td6 = '<td class="s2baia_flags_td"><span title="edit" class="dashicons dashicons-edit" onclick="s2b_assistants_list.editBot(event,' + bot_o.id + ",'"+this.appSuffix+'\' )"></span> ';
            
            td6 += '<span title="remove" class="dashicons dashicons-trash"  onclick="s2b_chatbot_list.removeRow(event,' + bot_o.id + ')"></span></td>';
            tr = tr + td1 + td2 + td3 + td4 + td5 + td6 + '</tr>';
            rows = rows + tr;
        }
        tbody.innerHTML = rows;

        this.showPagination(pagidata);
        let totals = document.querySelectorAll(this.totalRows);
        for (let i = 0; i < totals.length; i++) {
            let total = totals[i];
            total.innerHTML = 'Total: ' + res.total + ' items';
        }

        let new_table_container_height = this.setTableContainerHeight();//
        if (new_table_container_height > s2baia_instruction_table_height) {
            s2baia_instruction_table_height = new_table_container_height;
            let  tbl_div = document.querySelector(this.tableContainer);
            if (tbl_div) {
                tbl_div.style.height = s2baia_instruction_table_height + 'px';
            }
        }

        //s2baia_assistants = res.chat_bots;
    }
}

class S2baiaUtils{
    CopyToClipboardInnerHtml(e,elementid){
        e.preventDefault();
        let tarea = document.querySelector('#'+elementid);
        if(!tarea){
            return;
        }
        let lxt = tarea.innerHTML;
        if (!navigator.clipboard) {
            return;
        }

        navigator.clipboard.writeText(lxt).then(function() {
        alert(s2baia_copy_clipboard_sucess);
      }, function(err) {
        alert(s2baia_copy_clipboard_fail + ': '+ err);
      });

    }
}