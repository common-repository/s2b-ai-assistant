
/* config correction */

function s2baiaCountInstructionChar(par) {
    let len = par.value.length;
    if (len > 0) {
        document.querySelector('#s2baia_submit_edit_instruction').disabled = false;//s2baia_submit_edit_instruction
    } else {
        document.querySelector('#s2baia_submit_edit_instruction').disabled = true;
    }
}



function s2baiaToggleInstruction(e, instr_id) {
    e.preventDefault();

    //check input fields before store instruction
    let s2bdata = {'s2b_gpt_toggleinstructnonce': s2baia_toggleinstructionnonce};
    s2bdata['action'] = 's2b_gpt_toggle_instruction';
    s2bdata['id'] = instr_id;



    s2b_performAjax.call(s2b_toggle_instruction_result_dynamic, s2bdata);

}


function s2baiaRemoveInstruction(e) {
    e.preventDefault();
    s2baiaPutInstructionsLoader('#s2baia_new_instruction','right',30);
    s2baiaRemoveRow(e, s2baia_edited_instruction_id);
}

function s2baiaRemoveRow(e, instr_id) {

    e.preventDefault();
    let wantremove = confirm(s2b_message_instruction_confirm_delete + ':' + instr_id);
    if (!wantremove) {
        return 0;
    }

    //check input fields before store instruction
    let s2bdata = {'s2b_gpt_delinstructnonce': s2b_gpt_delinstructnonce};
    s2bdata['action'] = 's2b_gpt_delete_instruction';
    s2bdata['id'] = instr_id;
    document.querySelector('#s2baia_page').value = 1;
    s2b_performAjax.call(s2b_delete_instruction, s2bdata);

}

function s2baiaEditInstruction(e, instr_id) {
    e.preventDefault();
    let str_instr_id = instr_id + '';
    s2baia_edited_instruction_id = instr_id;
    document.querySelector('#s2baia_idinstruction').value = instr_id;//
    document.querySelector('#s2baia_id_instruction_lbl').innerHTML = 'ID:' + instr_id;
    if (str_instr_id in s2baia_instructions && 'instruction' in s2baia_instructions[str_instr_id]) {
        let instruction = s2baia_instructions[str_instr_id]['instruction'];
        document.querySelector('#s2baia_instruction').value = instruction;

        let buttons = {'s2baia_submit_edit_instruction': [1, 1, 'Save'], 's2baia_saveasnew_instruction': [1, 1, ''],
            's2baia_new_instruction': [1, 1, ''], 's2baia_remove_instruction': [1, 1, '']};
        s2baiaToggleButtons(buttons);
        let typeof_instruction = s2baia_instructions[str_instr_id]['typeof_instruction'];//s2baia_instruction_type typeof_instruction
        let typeof_instruction_element = document.querySelector('#s2baia_instruction_type');
        if (typeof_instruction_element) {
            if (typeof_instruction === '1') {
                typeof_instruction_element.value = 1;
            } else if (typeof_instruction === '2') {
                typeof_instruction_element.value = 2;
            }
            typeof_instruction_element.scrollIntoView({behavior: "smooth"});
        }
        let disabled = s2baia_instructions[str_instr_id]['disabled'];//s2baia_instruction_type typeof_instruction
        let disabled_element = document.querySelector('#s2baia_disabled');
        if (disabled_element) {
            if (disabled == 1) {
                disabled_element.checked = true;
            } else {
                disabled_element.checked = false;
            }
        }
    }

}

function s2baiaNewInstruction(e) {
    e.preventDefault();
    document.querySelector('#s2baia_idinstruction').value = 0;
    s2baia_edited_instruction_id = 0;
    let s2baia_instruction_el = document.querySelector('#s2baia_instruction');

    if (s2baia_instruction_el) {
        s2baia_instruction_el.value = '';
    }

    let s2baia_disabled = document.querySelector('#s2baia_disabled');//.checked;
    if (s2baia_disabled) {
        s2baia_disabled.checked = 0;
    }
    let s2baia_instruction_type = document.querySelector('#s2baia_instruction_type');
    if (s2baia_instruction_type) {
        s2baia_instruction_type.value = 1;
    }
    let s2baia_id_instruction_lbl = document.querySelector('#s2baia_id_instruction_lbl');
    if (s2baia_id_instruction_lbl) {
        s2baia_id_instruction_lbl.innerHTML = 'ID:';
    }
    let buttons = {'s2baia_submit_edit_instruction': [1, 0, 'Add'], 's2baia_saveasnew_instruction': [0, 1, ''],
        's2baia_new_instruction': [0, 1, ''], 's2baia_remove_instruction': [0, 1, '']};
    s2baiaToggleButtons(buttons);
}

function s2baiaStoreNewInstruction(e) {
    e.preventDefault();
    document.querySelector('#s2baia_idinstruction').value = 0;
    s2baia_edited_instruction_id = 0;
    s2baiaStoreInstruction(e);
}


function s2baiaChangePerPage(el) {
    document.querySelector('#s2baia_page').value = 0;
    s2baiaLoadInstructions('#s2baia_container','left',-70);
}



function s2baiaNextPage(e) {
    e.preventDefault();
    let current_page = document.querySelector('#s2baia_page').value;
    document.querySelector('#s2baia_page').value = (+current_page) + 1;
    s2baiaDisablePointerEvents('.s2bnext.page-numbers');

    s2baiaLoadInstructions('#s2baia_container','left',-70);
}

function s2baiaPrevPage(e) {
    e.preventDefault();
    let current_page = document.querySelector('#s2baia_page').value;
    document.querySelector('#s2baia_page').value = (+current_page) - 1;
    s2baiaDisablePointerEvents('.s2bprevious.page-numbers');

    s2baiaLoadInstructions('#s2baia_container','left',-70);
}

function s2baiaDisablePointerEvents(classes = '') {
    let els = document.querySelectorAll(classes);
    let els_l = els.length;
    for (let i = 0; i < els_l; i++) {
        els[i].style.pointerEvents = 'none';//
    }
}

function s2baiaLoadInstructionsE(e) {
    e.preventDefault();
    document.querySelector('#s2baia_page').value = 0;
    s2baiaLoadInstructions('#s2baia_container','right',-70);
}

function s2baiaSearchKeyUp(e) {
    e.preventDefault();
    if (e.key === 'Enter' || e.keyCode === 13) {
        document.querySelector('#s2baia_page').value = 0;
        s2baiaLoadInstructions('.s2baia_button_container.s2baia_bloader');
    }

}

function s2baiaLoadInstructions(element_selector,side,distance) {//side can have 
    //values: left , right; distance in pixels

    let s2bdata = {'s2b_gpt_loadnonce': s2b_gpt_loadnonce};
    s2bdata['action'] = 's2b_gpt_load_instruction';
    s2bdata['instructions_per_page'] = document.querySelector('#instructions_per_page').value;
    s2bdata['search'] = document.querySelector('#s2baia_search').value;
    s2bdata['page'] = document.querySelector('#s2baia_page').value;
    s2baiaPutInstructionsLoader(element_selector,side,distance);
    s2b_performAjax.call(s2b_load_instruction_result, s2bdata);

}

function s2baiaClearSearch(e) {
    e.preventDefault();
    let search = document.querySelector('#s2baia_search');
    if (search) {
        search.value = '';
        document.querySelector('#s2baia_page').value = 0;
        s2baiaLoadInstructions('#s2baia_container','right',-70);

    }
}
//s2baia_submit_edit_instruction
function s2baiaStoreInstruction(e) {

    e.preventDefault();
    
    //check input fields before store instruction
    let s2bdata = {'s2b_gpt_confinstructnonce': s2b_gpt_confinstructnonce};
    s2bdata['action'] = 's2b_gpt_conf_store_instruction';

    let s2baia_instruction_el = document.querySelector('#s2baia_instruction');

    if (!s2baia_instruction_el) {
        return;
    }
    s2baia_instruction_el.classList.remove("s2baia_error_field");
    let instruction = s2baia_instruction_el.value;
    if (instruction.length === 0) {
        //s2baia_error_field
        s2baia_instruction_el.setAttribute("class", "s2baia_error_field");
        return;
    }
    s2bdata['instruction'] = instruction;

    let s2baia_disabled = document.querySelector('#s2baia_disabled').checked;
    if (s2baia_disabled) {
        s2bdata['disabled'] = 1;
    } else {
        s2bdata['disabled'] = 0;
    }
    s2bdata['id_instruction'] = document.querySelector('#s2baia_idinstruction').value;
    s2bdata['instruction_type'] = document.querySelector('#s2baia_instruction_type').value;
    
    if (s2bdata['id_instruction'] > 0) {
        s2baiaPutInstructionsLoader('#s2baia_new_instruction','right',30);
        s2b_performAjax.call(s2b_edit_instruction_result_dynamic, s2bdata);
    } else {
        s2baiaPutInstructionsLoader('#s2baia_submit_edit_instruction','right',30);
        s2b_performAjax.call(s2b_new_instruction_result_dynamic, s2bdata);
    }
}

function s2baiaShowPagination(data) {

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
    let prev_page_as = document.querySelectorAll('.s2bprevious.page-numbers');
    let next_page_as = document.querySelectorAll('.s2bnext.page-numbers');
    for (let i = 0; i < prev_page_as.length; i++) {
        prev_page_a = prev_page_as[i];
        prev_page_a.style.pointerEvents = '';//
        if (show_prev) {
            prev_page_a.style.display = 'inline-block';
        } else {
            prev_page_a.style.display = 'none';
        }
    }

    for (let i = 0; i < next_page_as.length; i++) {
        next_page_a = next_page_as[i];
        next_page_a.style.pointerEvents = '';//
        if (show_next) {

            next_page_a.style.display = 'inline-block';

        } else {
            next_page_a.style.display = 'none';
        }
    }



    let totals = document.querySelectorAll('.s2baia_total_instructions');
    for (let i = 0; i < totals.length; i++) {
        let total = totals[i];
        total.innerHTML = 'Total: ' + total_instructions + ' items';
    }
    let page_numbers = document.querySelectorAll('.page-numbers.current');
    for (let i = 0; i < page_numbers.length; i++) {
        let page_number = page_numbers[i];
        page_number.innerHTML = page;
    }
}



let s2b_load_instruction_result = {

    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'result' in res || res.result != 200) {
            return;
        }
        let pagidata = {'page': res.page, 'items_per_page': res.instructions_per_page, 'total': res.total};//instructions_per_page

        //update appropriate row in table
        s2baia_instructions = res.js_instructions;
        //s2baia_edited_instruction_id = 0;
        let tbody = document.querySelector('#s2baia-the-list');
        if (!tbody) {
            return 0;
        }
        tbody.innerHTML = '';
        let rows = '';
        for (let idx in s2baia_instructions) {
            let instruction_o = s2baia_instructions[idx];
            let tr = '<tr class="';
            if (instruction_o.disabled === '1') {
                tr += 's2baia_disabled_text';
            }
            tr += '">';
            let td1 = '<td class="id_column">' + instruction_o.id + '</td>';
            let td2 = '<td><a href="#" onclick="s2baiaEditInstruction(event,' + instruction_o.id + ' )" id="s2baia_instr_href_' + instruction_o.id + '">' + instruction_o.instruction + '</a></td>';
            let typeofinstr = '';
            if (instruction_o.typeof_instruction === '1') {
                typeofinstr = s2baia_text_edit_label;
            } else {
                typeofinstr = s2baia_code_edit_label;
            }
            let td3 = '<td class="mvertical"><span id="s2baia_type_instr_span_' + instruction_o.id + '">' + typeofinstr + '</span></td>';
            let disabled = '';
            if (instruction_o.disabled === '1') {
                disabled = s2baia_disabled_label;
            } else {
                disabled = s2baia_enabled_label;
            }
            let td4 = '<td class=""><span id="s2baia_enabled_span_' + instruction_o.id + '">' + disabled + '</span></td>';
            let td5 = '<td class="s2baia_user mvertical"><span>' + instruction_o.user_id + '</span></td>';
            let td6 = '<td class="s2baia_flags_td"><span title="edit" class="dashicons dashicons-edit" onclick="s2baiaEditInstruction(event,' + instruction_o.id + ')"></span> ';
            if (instruction_o.disabled === '1') {
                td6 += '<span title="enable" class="dashicons dashicons-insert" onclick="s2baiaToggleInstruction(event,' + instruction_o.id + ')"></span> ';
            } else {
                td6 += '<span title="disable" class="dashicons dashicons-remove" onclick="s2baiaToggleInstruction(event,' + instruction_o.id + ')"></span> ';
            }
            td6 += '<span title="remove" class="dashicons dashicons-trash"  onclick="s2baiaRemoveRow(event,' + instruction_o.id + ')"></span></td>';
            tr = tr + td1 + td2 + td3 + td4 + td5 + td6 + '</tr>';
            rows = rows + tr;
        }
        tbody.innerHTML = rows;

        s2baiaShowPagination(pagidata);
        let totals = document.querySelectorAll('.s2baia_total_instructions');
        for (let i = 0; i < totals.length; i++) {
            let total = totals[i];
            total.innerHTML = 'Total: ' + res.total + ' items';
        }

        let new_table_container_height = s2baiaSetTableContainerHeight();//
        if (new_table_container_height > s2baia_instruction_table_height) {
            s2baia_instruction_table_height = new_table_container_height;
            let  tbl_div = document.querySelector('#s2baia_container');
            if (tbl_div) {
                tbl_div.style.height = s2baia_instruction_table_height + 'px';
            }
        }

    },

    ajaxComplete: s2baiaHideLoader
};

let s2b_delete_instruction = {

    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'result' in res ) {
            return;
        }
        
        if(res.result === 10){
            alert(res.msg + ' '+'only admin has acces to this operation');
            return;
        }
        
        let del_instruction = res.del_instruction;

        let link_to_delete = document.querySelector('#s2baia_instr_href_' + del_instruction);
        if (link_to_delete) {
            link_to_delete.parentElement.parentElement.remove();
        }
        delete s2baia_instructions[del_instruction];
        if (+s2baia_edited_instruction_id === +del_instruction) {
            s2baia_edited_instruction_id = 0;
        }

        let s2baia_idinstruction = document.querySelector('#s2baia_idinstruction').value;
        if (+del_instruction === +s2baia_idinstruction) {
            document.querySelector('#s2baia_idinstruction').value = 0;
            let s2baia_instruction = document.querySelector('#s2baia_instruction');
            if (s2baia_instruction) {
                s2baia_instruction.value = "";
            }
            let s2baia_instruction_type = document.querySelector('#s2baia_instruction_type');
            if (s2baia_instruction_type) {
                s2baia_instruction_type.value = 1;
            }

            let disabled_element = document.querySelector('#s2baia_disabled');
            if (disabled_element) {
                disabled_element.checked = false;
            }

            let s2baia_id_instruction_lbl = document.querySelector('#s2baia_id_instruction_lbl');
            if (s2baia_id_instruction_lbl) {
                s2baia_id_instruction_lbl.innerHTML = 'ID:';
            }
            let buttons = {'s2baia_submit_edit_instruction': [1, 0, 'Add'], 's2baia_saveasnew_instruction': [0, 1, ''],
                's2baia_new_instruction': [0, 1, ''], 's2baia_remove_instruction': [0, 1, '']};
            s2baiaToggleButtons(buttons);
        }
        s2baiaLoadInstructions('.s2baia_button_container.s2baia_bloader');

    },

    ajaxComplete: s2baiaHideLoader
};

let s2b_edit_instruction_result_dynamic = {
    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'result' in res || res.result != 200) {
            return;
        }
        //update appropriate row in table
        let modified_instruction = res.new_instruction;
        let modified_instruction_id = modified_instruction.id;
        let modified_instruction_id_str = modified_instruction_id + '';
        if(modified_instruction_id_str in s2baia_instructions){
            s2baia_instructions[modified_instruction_id_str]['instruction'] = modified_instruction.instruction;
            s2baia_instructions[modified_instruction_id_str]['typeof_instruction'] = modified_instruction.instruction_type;
            s2baia_instructions[modified_instruction_id_str]['disabled'] = modified_instruction.disabled;
        }


        let s2baia_instr_href = document.querySelector('#s2baia_instr_href_' + modified_instruction_id);
        if (!s2baia_instr_href) {
            return;
        }

        s2baia_instr_href.innerHTML = modified_instruction.instruction;

        let s2baia_type_instr_span = document.querySelector('#s2baia_type_instr_span_' + modified_instruction_id);
        if (!s2baia_type_instr_span) {
            return;
        }

        if (modified_instruction.instruction_type === 2) {
            s2baia_type_instr_span.innerHTML = s2baia_code_edit_label;

        } else if (modified_instruction.instruction_type === 1) {
            s2baia_type_instr_span.innerHTML = s2baia_text_edit_label;
        }

        let s2baia_type_instr_disabled = document.querySelector('#s2baia_enabled_span_' + modified_instruction_id);
        if (!s2baia_type_instr_disabled) {
            return;
        }


        let s2baia_tr = s2baia_instr_href.parentElement.parentElement;//get parent tr element
        if (modified_instruction.disabled === 1) {
            s2baia_type_instr_disabled.innerHTML = 'disabled';
            s2baia_tr.classList.add("s2baia_disabled_text");
            let rmdashicon = s2baia_tr.querySelector('span.dashicons-remove');
            if (rmdashicon) {
                rmdashicon.classList.add('dashicons-insert');
                rmdashicon.classList.remove('dashicons-remove');
            }
        } else {
            s2baia_type_instr_disabled.innerHTML = 'enabled';
            s2baia_tr.classList.remove("s2baia_disabled_text");
            let insdashicon = s2baia_tr.querySelector('span.dashicons-insert')
            if (insdashicon) {
                insdashicon.classList.add('dashicons-remove');
                insdashicon.classList.remove('dashicons-insert');
            }
        }


    },

    ajaxComplete: s2baiaHideLoader
};

let s2b_new_instruction_result_dynamic = {

    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {
        
        if (!'result' in res || res.result != 200) {

            return;

        }
        //update appropriate row in table
        let modified_instruction = res.new_instruction;
        let modified_instruction_id = modified_instruction.id;
        //let modified_instruction_id_str = modified_instruction_id + '';
        if (modified_instruction_id > 0) {
            alert(s2b_message_instruction_store_success + ':' + modified_instruction_id);
        } else {
            alert(s2b_message_instruction_store_error);
            return 0;
        }
        let s2baia_id_instruction_lbl = document.querySelector('#s2baia_id_instruction_lbl');
        if (s2baia_id_instruction_lbl) {
            s2baia_id_instruction_lbl.innerHTML = 'ID:' + modified_instruction_id;
        }

        let s2baia_idinstruction = document.querySelector('#s2baia_idinstruction');
        if (s2baia_idinstruction) {
            s2baia_idinstruction.value = modified_instruction_id;
        }
        s2baia_edited_instruction_id = modified_instruction_id;
        let buttons = {'s2baia_submit_edit_instruction': [1, 1, 'Save'], 's2baia_saveasnew_instruction': [1, 1, ''],
            's2baia_new_instruction': [1, 1, ''], 's2baia_remove_instruction': [1, 1, '']};
        s2baiaToggleButtons(buttons);
        document.querySelector('#s2baia_page').value = 1;
        s2baiaLoadInstructions('.s2baia_button_container.s2baia_bloader');


    },

    ajaxComplete: s2baiaHideLoader
};

let s2b_toggle_instruction_result_dynamic = {
    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'result' in res || res.result != 200) {

            return;

        }
        //update appropriate row in table
        let modified_instruction = res.new_instruction;
        let modified_instruction_id = modified_instruction.id;
        let modified_instruction_id_str = modified_instruction_id + '';


        let s2baia_type_instr_disabled = document.querySelector('#s2baia_enabled_span_' + modified_instruction_id);
        if (!s2baia_type_instr_disabled) {
            return;
        }
        let s2baia_instr_href = document.querySelector('#s2baia_instr_href_' + modified_instruction_id);
        if (!s2baia_instr_href) {
            return;
        }
        s2baia_instructions[modified_instruction_id_str]['disabled'] = modified_instruction.disabled;

        let s2baia_tr = s2baia_instr_href.parentElement.parentElement;

        if (modified_instruction.disabled === 1) {
            s2baia_type_instr_disabled.innerHTML = 'disabled';
            s2baia_tr.classList.add("s2baia_disabled_text");
            let remove_icon = s2baia_tr.querySelector('span.dashicons-remove');
            if (remove_icon) {
                remove_icon.classList.add('dashicons-insert');
                remove_icon.classList.remove('dashicons-remove');
            }
        } else {
            s2baia_type_instr_disabled.innerHTML = 'enabled';
            s2baia_tr.classList.remove("s2baia_disabled_text");
            let insert_icon = s2baia_tr.querySelector('span.dashicons-insert');
            if (insert_icon) {
                insert_icon.classList.add('dashicons-remove');
                insert_icon.classList.remove('dashicons-insert');
            }
        }
        if(modified_instruction_id == s2baia_edited_instruction_id){//if id of clicked instruction is equal 
            // instruction that is edited in form then we need to change disable status of edited instruction
            let s2baia_disabled = document.querySelector('#s2baia_disabled')
            if (s2baia_disabled) {
                if(modified_instruction.disabled === 1){
                    s2baia_disabled.checked = true;
                }else{
                    s2baia_disabled.checked = false;
                }
            }
        }

    },

    ajaxComplete: s2baiaHideLoader
};

function s2baiaToggleButtons(buttons) {
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


/*config models and general*/

function s2baiaSetTableContainerHeight() {
    let table_element = document.querySelector('#s2baia_instructions');
    if(!table_element){
        return;
    }
    let table_height = table_element.offsetHeight;
    let table_element2 = document.querySelector('#s2baia_search_submit');
    if(!table_element2){
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


function s2baiaSaveGeneral(e) {
    e.preventDefault();
    
    if(!jQuery){
        alert(s2baia_jquery_is_not_installed);
        return;
    }
    let genForm = jQuery('#s2baia_gen_form');
    let data = genForm.serialize();
    s2baiaPutGeneralLoader();
    s2b_performAjax.call(s2b_general_tab_dynamic, data);

}


function s2baiaSaveModels(e) {
    e.preventDefault();
    if(!jQuery){
        alert(s2baia_jquery_is_not_installed);
        return;
    }
    let genForm = jQuery('#s2baia_models_form');
    let data = genForm.serialize();
    s2baiaPutModelsLoader();
    s2b_performAjax.call(s2b_models_tab_dynamic, data);
}

function s2baiaPutModelsLoader(){
    let targetelement = document.querySelector('#s2baia_refresh_models');// event.target;
    let loader = document.querySelector('.s2baia-models-loader');
    
    if(targetelement && loader){
        let parentel = targetelement.parentElement.parentElement;
        let rect = targetelement.getBoundingClientRect();
        if(rect && parentel){
            let parentelrect = parentel.getBoundingClientRect();
            let rght = rect.right - parentelrect.left;
            loader.style.left = (rght+20) + 'px';
        }
    }
}

function s2baiaPutInstructionsLoader(element_selector,side,distance,loader_selector){

    let targetelement = document.querySelector(element_selector);// '.s2baia_button_container.s2baia_bloader'
    //let loader = document.querySelector('.s2baia-instructions-loader');
    let loader = null;
    if(loader_selector){
        loader = document.querySelector(loader_selector);
    }else{
        loader = document.querySelector('.s2baia-instructions-loader');
    }
    if(targetelement && loader){

        let rect = targetelement.getBoundingClientRect();
        let rght = rect.right - rect.left;
        console.log(rect);
        console.log(rght);
        if(rect){
            if(side === 'left'){
                loader.style.left = (Math.round(rect.left) - Math.round(distance)) +  'px';
                loader.style.top = (Math.round(rect.top) - 5) +  'px';
            }
            if(side === 'right'){
                loader.style.left = (Math.round(rect.right) + Math.round(distance)) +  'px';
                loader.style.top = (Math.round(rect.top) - 5) +  'px';
            }
            
        }
        //console.log('left = ' + loader.style.left);
        //console.log('top = ' + loader.style.top);
    }
}

function s2baiaPutGeneralLoader(){
    let targetelement = document.querySelector('.s2baia_gbutton_container.s2baia_bloader');// event.target;
    let loader = document.querySelector('.s2baia-general-loader');
    if(targetelement && loader){

        let rect = targetelement.getBoundingClientRect();
       
        if(rect){
            loader.style.left =  '200px';

        }
    }
}

function s2baiaGetModels(event) {
    event.preventDefault();
    
    if (event.keyCode && event.keyCode === 13) {
        return;
    }

    s2baiaPutModelsLoader();
    
    let s2bdata = {'s2b_gpt_confnonce': s2b_gpt_confnonce};
    s2bdata['action'] = 's2b_gpt_conf_models';

    s2b_performAjax.call(s2b_model_list_dynamic, s2bdata);
}

function s2baiaShowLoader(loader_selector){

    if(!jQuery){
        return;
        
    }

    if(typeof loader_selector === 'string'){
        jQuery(loader_selector).css('display','grid');
    }else{
        jQuery('.s2baia-custom-loader').css('display','grid');
    }
    
}

function s2baiaHideLoader(){

    if(jQuery){
        jQuery('.s2baia-custom-loader').hide();
    }
}

let s2b_models_tab_dynamic = {
    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {
        
        s2b_alertResultMessage(res, s2baia_message_config_models_error1, s2baia_message_config_models_succes1);
        
    },

    ajaxComplete: s2baiaHideLoader
};

let s2b_general_tab_dynamic = {
    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {
        
        s2b_alertResultMessage(res, s2baia_message_config_general_error, s2baia_message_config_general_succes1);
        
    },

    ajaxComplete: s2baiaHideLoader
};

let s2b_model_list_dynamic = {
    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {
        if ('result' in res && res.result == 200) {
            if(!jQuery){
                alert(s2baia_jquery_is_not_installed);
                return;
            }
            jQuery('#s2baia_result_c').val(res.msg);
            let s2baia_models = res.models;
            let s2baia_expert_models = res.expert_models;
            let s2baia_models_container = document.querySelector('#s2baia_models_container');
            if (s2baia_models_container) {
                s2baia_models_container.innerHTML = '';
                for (let modelname in s2baia_models) {
                    let modelstatus = s2baia_models[modelname];
                    let expert_modelstatus = s2baia_expert_models[modelname];
                    let mdiv = document.createElement("div");
                    mdiv.setAttribute("class", "s2baia_c_opt");

                    let mcheckb = document.createElement("input");
                    mcheckb.setAttribute("type", "checkbox");
                    if (modelstatus == 1 || modelstatus == 3) {
                        mcheckb.setAttribute("checked", true);
                    }
                    mcheckb.setAttribute("name", 's2baia_models[' + modelname + ']');
                    mcheckb.setAttribute("id", modelname);

                    let mcheckblbl1_edit = document.createElement("label");
                    mcheckblbl1_edit.innerHTML = 'edit';
                    mcheckblbl1_edit.setAttribute("for", modelname);

                    let expert_mcheckb = document.createElement("input");
                    expert_mcheckb.setAttribute("type", "checkbox");
                    if (expert_modelstatus == 1 || expert_modelstatus == 3) {
                        expert_mcheckb.setAttribute("checked", true);
                    }
                    expert_mcheckb.setAttribute("name", 's2baia_expert_models[' + modelname + ']');
                    expert_mcheckb.setAttribute("id", 'expert' + modelname);

                    let mcheckblbl1_exp = document.createElement("label");
                    mcheckblbl1_exp.innerHTML = 'expert';
                    mcheckblbl1_exp.setAttribute("for", 'expert' + modelname);

                    let mcheckblbl1 = document.createElement("label");
                    mcheckblbl1.innerHTML = modelname;

                    s2baia_models_container.appendChild(mdiv);

                    mdiv.appendChild(mcheckblbl1);
                    mdiv.appendChild(document.createTextNode(" "));
                    mdiv.appendChild(mcheckb);
                    mdiv.appendChild(document.createTextNode(" "));
                    mdiv.appendChild(mcheckblbl1_edit);
                    mdiv.appendChild(document.createTextNode(" "));
                    mdiv.appendChild(expert_mcheckb);
                    mdiv.appendChild(document.createTextNode(" "));
                    mdiv.appendChild(mcheckblbl1_exp);
                }
            }
        }
        s2b_alertResultMessage(res, s2baia_message_config_models_error2, s2baia_message_config_models_succes2);
         
    },

    ajaxComplete: s2baiaHideLoader
};

function s2b_alertResultMessage(res, default_error, default_success){
    if (!'result' in res || res.result != 200) {
            if( 'msg' in res){
                alert(res.msg);
            }else{
                alert(default_error);
            }
            return false;
        }
        alert(default_success);
}



function s2baiaStoreConfig(e) {
    e.preventDefault();

}

function s2baia_preventDefault(event){
        if (event.key === "Enter") {
            event.preventDefault();
        }
    }

    function s2b_performAjax(data) {
        jQuery.ajax({
            url: s2baajaxAction,
            type: 'POST',
            dataType: 'json',
            context: this,
            data: data,
            beforeSend: this.ajaxBefore,
            success: this.ajaxSuccess,
            complete: this.ajaxComplete
        });
    }



/* correction metabox */


function s2baiaMetaSelectInstruction(e, instr_id) {
    e.preventDefault();

    s2baia_edited_instruction_id = instr_id;
    let instruction_element = document.querySelector('#s2baia_instruction');
    instruction_element.value = s2baia_instructions[instr_id]['instruction'];//
    instruction_element.scrollIntoView({behavior: "smooth"});

}

function s2baiaMetaNextPageIn(e) {
    e.preventDefault();
    let current_page = document.querySelector('#s2baia_page').value;
    document.querySelector('#s2baia_page').value = (+current_page) + 1;
    s2baiaDisablePointerEvents('.s2bnext.page-numbers');

    s2baiaMetaLoadInstructions();
}

function s2baiaMetaPrevPageIn(e) {
    e.preventDefault();
    let current_page = document.querySelector('#s2baia_page').value;
    document.querySelector('#s2baia_page').value = (+current_page) - 1;
    s2baiaDisablePointerEvents('.s2bprevious.page-numbers');

    s2baiaMetaLoadInstructions();
}

function s2baiaMetaLoadInstructions() {

    let s2bdata = {'s2b_gpt_loadnoncec': s2b_gpt_loadnoncec};
    s2bdata['action'] = 's2b_gpt_load_correct_instruction';
    s2bdata['instructions_per_page'] = document.querySelector('#instructions_per_page').value;
    s2bdata['search'] = document.querySelector('#s2baia_search').value;
    s2bdata['page'] = document.querySelector('#s2baia_page').value;
    s2bdata['show_enabled_only'] = 1;
    s2b_performAjax.call(s2b_load_correct_instruction_result, s2bdata);

}


let s2b_load_correct_instruction_result = {

    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'result' in res || res.result != 200) {
            return;
        }
        let pagidata = {'page': res.page, 'items_per_page': res.instructions_per_page, 'total': res.total};//instructions_per_page


        s2baia_instructions = res.js_instructions;
        let tbody = document.querySelector('#s2baia-the-list');
        if (!tbody) {
            return 0;
        }
        tbody.innerHTML = '';
        let rows = '';
        for (let idx in s2baia_instructions) {
            
            let instruction_o = s2baia_instructions[idx];
            let tr = '<tr class="';
            if (instruction_o.disabled === '1') {
                tr += 's2baia_disabled_text';
            }
            tr += '">';
            let td1 = '<td class="id_column">' + '<a href="#" onclick="s2baiaMetaSelectInstruction(event,' + instruction_o.id + ' )" >' + instruction_o.id + '</a></td>';
            let td2 = '<td><a href="#" onclick="s2baiaMetaSelectInstruction(event,' + instruction_o.id + ' )" id="s2baia_instr_href_' + instruction_o.id + '">' + instruction_o.instruction + '</a></td>';
            let typeofinstr = '';
            if (instruction_o.typeof_instruction === '1') {
                typeofinstr = s2baia_typeofinstr_text;
            } else {
                typeofinstr = s2baia_typeofinstr_code;
            }
            let td3 = '<td class="mvertical">' + '<a href="#" onclick="s2baiaMetaSelectInstruction(event,' + instruction_o.id + ' )" >' + typeofinstr + '</a></td>';

            let td4 = '';
            let td5 = '';
            let td6 = '';
            tr = tr + td1 + td2 + td3 + td4 + td5 + td6 + '</tr>';
            rows = rows + tr;
        }
        tbody.innerHTML = rows;

        s2baiaShowPagination(pagidata);
        let totals = document.querySelectorAll('.s2baia_total_instructions');
        for (let i = 0; i < totals.length; i++) {
            let total = totals[i];
            total.innerHTML = 'Total: ' + res.total + ' items';
        }


    },

    ajaxComplete: s2baiaHideLoader
};


    let s2b_correct_result_dynamic = {
        ajaxBefore: function () {
            s2baiaShowLoader();
        },

        ajaxSuccess: function (res) {
            if ('result' in res && res.result == 200) {
                jQuery('#s2baia_result_c').val(res.msg);
            } else if ('result' in res && res.result == 404) {
                jQuery('#s2baia_result_c').val(res.msg);
            }
        },

        ajaxComplete: function () {
            s2baiaHideLoader();
        }
    };

    let s2b_generate_result_dynamic = {
        ajaxBefore: function () {
            s2baiaShowLoader();
        },

        ajaxSuccess: function (res) {
            if ('result' in res && res.result == 200) {
                jQuery('#s2baia_response_e').val(res.msg);
            } else if ('result' in res && res.result == 404) {
                jQuery('#s2baia_response_e').val(res.msg);
            }
        },

        ajaxComplete: function () {
            s2baiaHideLoader();
        }
    };


function s2baiaMetaLoadInstructionsSearch(e) {
    e.preventDefault();
    document.querySelector('#s2baia_page').value = 0;
    s2baiaMetaLoadInstructions();
}

function s2baiaMetaClearSearch(e) {
    e.preventDefault();
    let search = document.querySelector('#s2baia_search');
    if (search && search.value.length > 0) {
        search.value = '';
        document.querySelector('#s2baia_page').value = 0;
        s2baiaMetaLoadInstructions();

    }
}

function s2baiaMetaClearText(e,elementid){
    e.preventDefault();
    let tarea = document.querySelector('#'+elementid);
    if(!tarea){
        return;
    }
    tarea.value = '';

}

function s2baiaMetaCopyToClipboard(e,elementid){
    e.preventDefault();
    let tarea = document.querySelector('#'+elementid);
    if(!tarea){
        return;
    }
    let lxt = tarea.value;
    if (!navigator.clipboard) {
        return;
    }
    
    navigator.clipboard.writeText(lxt).then(function() {
    alert(s2baia_copy_clipboard_sucess);
  }, function(err) {
    alert(s2baia_copy_clipboard_fail + ': '+ err);
  });
  
}




function s2baiaMetaSearchKeyUp(e) {
    e.preventDefault();
    if (e.key === 'Enter' || e.keyCode === 13) {
        document.querySelector('#s2baia_page').value = 0;
        s2baiaMetaLoadInstructions();

    }

}


/*Metabox  Generate tab functions */


    
    let s2baia_radion_gen = 2;
    let s2baia_radion_lst2 = ['Deleted', 'User'];
    function s2baiaAddField(plusElement) {

        let displayButton = document.querySelector("#s2baia_response_td");
        let tbody = document.querySelector('#s2baia_expert_tbody');

        let s2biaia_cur_role = 'User';
        // creating the div container.
        for (let i = s2baia_radion_gen - 1; i > 0; i--) {
            if (s2baia_radion_lst2[i] === 'User') {
                s2biaia_cur_role = 'Assistant';
                break;
            }
            if (s2baia_radion_lst2[i] === 'Assistant') {
                s2biaia_cur_role = 'User';
                break;
            }

        }

        let tr = document.createElement("tr");
        tr.setAttribute("class", "s2baia_field");

        let td = document.createElement("td");
        td.setAttribute("colspan", "3");
        // Creating the textarea element.

        let radiodiv = document.createElement("div");

        radiodiv.setAttribute("class", "s2baia_halfscreen");

        let radiolbl1 = document.createElement("label");
        radiolbl1.innerHTML = s2baia_generate_assistant;

        let radioel1 = document.createElement("input");
        radioel1.setAttribute("type", "radio");
        radioel1.setAttribute("class", "s2baia_act");
        radioel1.setAttribute("name", "s2baia_actor" + s2baia_radion_gen);
        radioel1.setAttribute("id", "s2baia_actor_ae_" + s2baia_radion_gen);
        radioel1.setAttribute("id_gen_val", s2baia_radion_gen);
        radioel1.setAttribute("value", "Assistant");
        radioel1.setAttribute("onchange", "s2baiaRadioChange(" + s2baia_radion_gen + ", 'ae')");

        if (s2biaia_cur_role === 'Assistant') {
            radioel1.setAttribute("checked", true);
        }
        radiolbl1.setAttribute("for", "s2baia_actor_ae_" + s2baia_radion_gen);

        let radiodiv2 = document.createElement("div");
        radiodiv2.setAttribute("class", "s2baia_halfscreen");
        let radiolbl2 = document.createElement("label");
        radiolbl2.innerHTML = s2baia_generate_user;
        let radioel2 = document.createElement("input");
        radioel2.setAttribute("type", "radio");
        radioel2.setAttribute("class", "s2baia_act");
        radioel2.setAttribute("name", "s2baia_actor" + s2baia_radion_gen);
        radioel2.setAttribute("id", "s2baia_actor_ue_" + s2baia_radion_gen);
        radioel2.setAttribute("id_gen_val", s2baia_radion_gen);
        radioel2.setAttribute("value", "User");
        radioel2.setAttribute("onchange", "s2baiaRadioChange(" + s2baia_radion_gen + ", 'ue')");

        if (s2biaia_cur_role === 'User') {
            radioel2.setAttribute("checked", true);
        }
        radiolbl2.setAttribute("for", "s2baia_actor_ue_" + s2baia_radion_gen);

        let textareadiv = document.createElement("div");//
        textareadiv.setAttribute("class", "s2baia_2actor");

        let textarea = document.createElement("textarea");

        textarea.setAttribute("name", "s2baia_message_e_" + s2baia_radion_gen);
        textarea.setAttribute("id", "s2baia_message_e_" + s2baia_radion_gen);

        // Creating the textarea element.

        let plusminusdiv = document.createElement("div");//
        plusminusdiv.setAttribute("class", "s2baia_actor");
        // Creating the plus span element.
        let plus = document.createElement("span");
        plus.setAttribute("onclick", "s2baiaAddField(this)");
        let plusText = document.createTextNode("+");
        plus.appendChild(plusText);

        // Creating the minus span element.
        let minus = document.createElement("span");
        minus.setAttribute("onclick", "s2baiaRemoveField(this," + s2baia_radion_gen + ")");
        let minusText = document.createTextNode("-");
        minus.appendChild(minusText);

        // Adding the elements to the DOM.
        tbody.insertBefore(tr, displayButton);
        tr.appendChild(td);


        radiodiv.appendChild(radioel1);
        radiodiv.appendChild(radiolbl1);
        td.appendChild(radiodiv);


        radiodiv2.appendChild(radioel2);
        radiodiv2.appendChild(radiolbl2);
        td.appendChild(radiodiv2);

        textareadiv.appendChild(textarea);
        td.appendChild(textareadiv);

        plusminusdiv.appendChild(plus);
        plusminusdiv.appendChild(minus);
        td.appendChild(plusminusdiv);




        // Un hiding the minus sign.
        plusElement.nextElementSibling.style.display = "inline-block"; // the minus sign
        // Hiding the plus sign.
        plusElement.style.display = "none"; // the plus sign

        s2baia_radion_lst2[s2baia_radion_gen] = s2biaia_cur_role;
        s2baia_radion_gen += 1;
    }

    function s2baiaRadioChange(gen_id, suffix) {

        let el_id = 's2baia_actor_' + suffix + '_' + gen_id;
        let el_clicked = document.querySelector('#' + el_id);
        if (el_clicked) {
            let e_c_value = el_clicked.value;
            console.log(e_c_value);
            s2baia_radion_lst2[gen_id] = e_c_value;
            console.log(s2baia_radion_lst2);

        }

    }

    function s2baiaRemoveField(minusElement, rmidx) {
        minusElement.parentElement.parentElement.parentElement.remove();
        s2baia_radion_lst2[rmidx] = 'Deleted';
        console.log(s2baia_radion_lst2);
    }


/* config chatbot*/
/*general tab*/
function s2baiaSaveChatbotGeneral(e) {
    e.preventDefault();
    
    if(!jQuery){
        alert(s2baia_jquery_is_not_installed);
        return;
    }
    let genForm = jQuery('#s2baia_chatbot_gen_form');
    let data = genForm.serialize();
    s2baiaPutChatbotGeneralLoader();
    s2b_performAjax.call(s2b_chatbot_general_tab_dynamic, data);

}

function s2baiaPutChatbotGeneralLoader(){
    let targetelement = document.querySelector('.s2baia_gbutton_container.s2baia_bloader');// event.target;
    let loader = document.querySelector('.s2baia-general-loader');
    if(targetelement && loader){

        let rect = targetelement.getBoundingClientRect();
       
        if(rect){
            loader.style.left =  '200px';

        }
    }
}

let s2b_chatbot_general_tab_dynamic = {
    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {
        
        s2b_alertResultMessage(res, s2baia_message_config_general_error, s2baia_message_config_general_succes1);
        
    },

    ajaxComplete: s2baiaHideLoader
};

/*styles tab*/
function s2baiaSaveChatbotStyles(e) {
    e.preventDefault();
    
    if(!jQuery){
        alert(s2baia_jquery_is_not_installed);
        return;
    }
    let genForm = jQuery('#s2baia_styles_form');
    let data = genForm.serialize();
    s2baiaPutChatbotStylesLoader();
    s2b_performAjax.call(s2b_chatbot_styles_tab_dynamic, data);

}

function s2baiaPutChatbotStylesLoader(){
    let targetelement = document.querySelector('.s2baia_gbutton_container.s2baia_bloader');// event.target;
    let loader = document.querySelector('.s2baia-general-loader');
    if(targetelement && loader){

        let rect = targetelement.getBoundingClientRect();
       
        if(rect){
            loader.style.left =  '200px';

        }
    }
}

let s2b_chatbot_styles_tab_dynamic = {
    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {
        
        s2b_alertResultMessage(res, s2baia_message_config_styles_error, s2baia_message_config_styles_succes1);
        
    },

    ajaxComplete: s2baiaHideLoader
};

//Working with bot log records
let s2b_log_page_id = '#s2baia_page_log';
let s2b_logs_per_page_id = '#logs_per_page';

let s2b_toggle_selectedlog_result_dynamic = {
    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'result' in res || res.result != 200) {

            return;

        }
        //update appropriate row in table
        let new_selection = res.new_selection;
        let modified_instruction_id = new_selection.id;
        let modified_instruction_id_str = modified_instruction_id + '';


        let s2baia_type_instr_disabled = document.querySelector('#s2baia_selected_span_' + modified_instruction_id);
        if (!s2baia_type_instr_disabled) {
            return;
        }
        let s2baia_instr_href = document.querySelector('#s2baia_selected_href_' + modified_instruction_id);
        if (!s2baia_instr_href) {
            return;
        }
        s2baia_log_infos[modified_instruction_id_str]['selected'] = new_selection.selected;

        let s2baia_tr = s2baia_instr_href.parentElement.parentElement;

        if (new_selection.selected === 1) {
            s2baia_type_instr_disabled.innerHTML = 'selected';
            s2baia_tr.classList.add("s2baia_selected_text");
            let insert_icon = s2baia_tr.querySelector('span.dashicons-insert');
            if (insert_icon) {
                insert_icon.classList.add('dashicons-remove');
                insert_icon.classList.remove('dashicons-insert');
            }
            
        } else {
            s2baia_type_instr_disabled.innerHTML = '';
            s2baia_tr.classList.remove("s2baia_selected_text");
            let remove_icon = s2baia_tr.querySelector('span.dashicons-remove');
            if (remove_icon) {
                remove_icon.classList.add('dashicons-insert');
                remove_icon.classList.remove('dashicons-remove');
            }
        }
    },

    ajaxComplete: s2baiaHideLoader
};


function s2baiaLoadLogsE(e) {
    e.preventDefault();
    document.querySelector(s2b_log_page_id).value = 0;
    s2baiaLoadLogs('#s2baia_container','right',-70);
}

function s2baiaLoadLogs(element_selector,side,distance) {//side can have 
    //values: left , right; distance in pixels

    let s2bdata = {'s2b_gpt_loadnonce': s2b_gpt_loadnonce};
    s2bdata['action'] = 's2b_gpt_load_log';
    s2bdata['logs_per_page'] = document.querySelector(s2b_logs_per_page_id).value;
    s2bdata['search'] = document.querySelector('#s2baia_search').value;
    s2bdata['page'] = document.querySelector(s2b_log_page_id).value;
    s2baiaPutInstructionsLoader(element_selector,side,distance);
    s2b_performAjax.call(s2b_load_log_result, s2bdata);

}


let s2b_load_log_result = {

    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'result' in res || res.result != 200) {
            return;
        }
        let pagidata = {'page': res.page, 'items_per_page': res.logs_per_page, 'total': res.total};//

        //update appropriate row in table
        let s2baia_logs = res.log_records;

        let tbody = document.querySelector('#s2baia-the-list');
        if (!tbody) {
            return 0;
        }
        tbody.innerHTML = '';
        let rows = '';
        for (let idx in s2baia_logs) {
            let log_o = s2baia_logs[idx];
            let tr = '<tr class="';
            if (log_o.selected === '1') {
                tr += 's2baia_selected_text';
            }
            tr += '">';
            let td1 = '<td class="id_column">' + log_o.id + '</td>';
            let td2 = '<td><a href="#" onclick="s2baiaShowRecord(event,' + log_o.id + ' )" id="s2baia_selected_href_' + log_o.id + '">' + log_o.preview + '</a></td>';
          
            let td3 = '<td class="mvertical"><span id="s2baia_type_instr_span_' + log_o.id + '">' + log_o.visitor_info + '</span></td>';
            
            let td4 = '<td class=""><span id="s2baia_selected_span_' + log_o.id + '">' + log_o.chat_info + '</span></td>';
            let td5 = '<td class="s2baia_user mvertical"><span>' + log_o.created + '</span></td>';
            let td6 = '<td class="s2baia_flags_td"> ';//<span title="edit" class="dashicons dashicons-edit" onclick="s2baiaEditInstruction(event,' + log_o.id + ')"></span>
            if (log_o.selected === '1') {
                td6 += '<span title="unselect" class="dashicons dashicons-remove" onclick="s2baiaLogSelectRow(event,' + log_o.id + ')"></span> ';
            } else {
                td6 += '<span title="select" class="dashicons dashicons-insert" onclick="s2baiaLogSelectRow(event,' + log_o.id + ')"></span> ';
            }
            td6 += '<span title="remove" class="dashicons dashicons-trash"  onclick="s2baiaLogsRemoveRow(event,' + log_o.id + ')"></span></td>';
            tr = tr + td1 + td2 + td3 + td4 + td5 + td6 + '</tr>';
            rows = rows + tr;
        }
        tbody.innerHTML = rows;

        s2baiaShowPagination(pagidata);
        let totals = document.querySelectorAll('.s2baia_total_instructions');
        for (let i = 0; i < totals.length; i++) {
            let total = totals[i];
            total.innerHTML = 'Total: ' + res.total + ' items';
        }

        let new_table_container_height = s2baiaSetTableContainerHeight();//
        if (new_table_container_height > s2baia_instruction_table_height) {
            s2baia_instruction_table_height = new_table_container_height;
            let  tbl_div = document.querySelector('#s2baia_container');
            if (tbl_div) {
                tbl_div.style.height = s2baia_instruction_table_height + 'px';
            }
        }
        s2baia_log_messages = res.js_logmessages;
        s2baia_log_infos = res.js_loginfos;
        
        let s2b_thread_info = document.querySelector('#s2b_bot_thread_info');
        let s2b_thread_history = document.querySelector('#s2b_bot_thread_history');
        s2b_thread_info.innerHTML = '<div class="s2b-history" style="display: flex; flex-direction: column; margin-bottom: 5px;"><span>No data</span></div>';
        s2b_thread_history.innerHTML = '<div class="s2b-history" style="display: flex; flex-direction: column; margin-bottom: 5px;"><span>No data</span></div>';
        
    },

    ajaxComplete: s2baiaHideLoader
};


let s2b_delete_log = {

    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'result' in res ) {
            return;
        }
        
        if(res.result === 10){
            alert(res.msg + ' '+'only admin has acces to this operation');
            return;
        }
        
        let del_log = res.del_log;

        let link_to_delete = document.querySelector('#s2baia_selected_href_' + del_log);
        if (link_to_delete) {
            link_to_delete.parentElement.parentElement.remove();
        }
        delete s2baia_log_messages[del_log];
        delete s2baia_log_infos[del_log];
        s2baiaLoadLogs('#s2baia_container','left',-70);

        //s2baiaLoadInstructions('.s2baia_button_container.s2baia_bloader');

    },

    ajaxComplete: s2baiaHideLoader
};

function s2baiaNextLogPage(e) {
    e.preventDefault();
    let current_page = document.querySelector(s2b_log_page_id).value;
    document.querySelector(s2b_log_page_id).value = (+current_page) + 1;
    s2baiaDisablePointerEvents('.s2bnext.page-numbers');

    s2baiaLoadLogs('#s2baia_container','left',-70);
}

function s2baiaChangeLogPerPage(el) {
    document.querySelector(s2b_log_page_id).value = 0;
    s2baiaLoadLogs('#s2baia_container','left',-70);
}

function s2baiaPrevLogPage(e) {
    e.preventDefault();
    let current_page = document.querySelector(s2b_log_page_id).value;
    document.querySelector(s2b_log_page_id).value = (+current_page) - 1;
    s2baiaDisablePointerEvents('.s2bprevious.page-numbers');

    s2baiaLoadLogs('#s2baia_container','left',-70);
}

function s2baiaSearchLogKeyUp(e) {
    e.preventDefault();
    if (e.key === 'Enter' || e.keyCode === 13) {
        document.querySelector(s2b_log_page_id).value = 0;
        s2baiaLoadLogs('.s2baia_button_container.s2baia_bloader');
    }

}


function s2baiaLogsClearSearch(e) {
    e.preventDefault();
    let search = document.querySelector('#s2baia_search');
    if (search) {
        search.value = '';
        document.querySelector(s2b_log_page_id).value = 0;
        s2baiaLoadLogs('#s2baia_container','right',-70);

    }
}

function s2baiaLogsRemoveRow(e, log_id) {

    e.preventDefault();
    let wantremove = confirm(s2b_message_log_confirm_delete + ':' + log_id);
    if (!wantremove) {
        return 0;
    }

    //check input fields before store instruction
    let s2bdata = {'s2b_bot_dellognonce': s2b_bot_dellognonce};
    s2bdata['action'] = 's2b_gpt_delete_log';
    s2bdata['id'] = log_id;
    document.querySelector('#s2baia_page_log').value = 1;
    s2baiaPutInstructionsLoader('#s2baia_container','left',-70);
    s2b_performAjax.call(s2b_delete_log, s2bdata);

}


function s2baiaLogSelectRow(e, instr_id) {
    e.preventDefault();

    //check input fields before store instruction
    let s2bdata = {'s2b_gpt_toggleselectionnonce': s2baia_toggleselectionnonce};
    s2bdata['action'] = 's2b_gpt_toggle_selectionlog';
    s2bdata['id'] = instr_id;



    s2b_performAjax.call(s2b_toggle_selectedlog_result_dynamic, s2bdata);

}



let s2b_changemode_log = {

    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'cd' in res ) {
            return;
        }
        console.log(res);
        let s2baia_turn_log = document.querySelector('#s2baia_turn_log');
        if(!s2baia_turn_log ){
            return;
        }
        
        if(res.cd == 1){
            s2baia_turn_log.innerHTML = s2b_bot_turnon_msg;
        }else{
            s2baia_turn_log.innerHTML = s2b_bot_turnoff_msg;
        }

    },

    ajaxComplete: s2baiaHideLoader
};



function s2baiaLogConversation(allowlog) {
    let s2bdata = {'s2b_changemode_lognonce': s2b_changemode_lognonce};
    s2bdata['action'] = 's2b_bot_switchlogmode';
    s2bdata['allow'] = allowlog;
    
    s2b_performAjax.call(s2b_changemode_log, s2bdata);
}

let s2b_bot_logtext = {

    ajaxBefore: s2baiaShowLoader,

    ajaxSuccess: function (res) {

        if (!'cd' in res ) {
            return;
        }
        console.log(res);
    },

    ajaxComplete: s2baiaHideLoader
};

function s2baiaLogText(logtext) {
    let s2bdata = {'s2b_changemode_lognonce': s2b_changemode_lognonce};
    s2bdata['action'] = 's2b_bot_logtext';
    s2bdata['s2b_text'] = logtext;
    
    s2b_performAjax.call(s2b_bot_logtext, s2bdata);
}

