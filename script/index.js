let btn = document.querySelector('.content-2');
let div = document.querySelector('.content-1');
let msg = document.querySelector('.response-message');
let form = document.querySelector('form');
let table = document.querySelector('table');
let btn_edits = document.querySelectorAll('.btn-edit');

function createEl(msg, text){
    let el = document.createElement('div');
    el.classList.add('message');
    el.classList.add('card');
    el.innerHTML = text;
    if(msg!==' '){
        msg.append(el);
    }
    return el;
}

function setBackdrop(el){
    el.classList.remove('d-none');
}

function removeBackdrop(el){
    el.classList.add('d-none');
}

function setformStyle(form){
    form.classList.add('w-50','form-edit');
    form.classList.remove('d-none');
}

function removeform(form) {
    form.classList.remove('w-50','form-edit');
    form.classList.add('d-none');
}

btn_edits.forEach(btn=>{
    btn.addEventListener('click', e=>{
        let backdrop = document.querySelector('.backdrop');
        setBackdrop(backdrop);
        setformStyle(form);
        if(getEditData(e.target.getAttribute('data-id'))){
            removeBackdrop(backdrop);
            removeform(form);
        }
        backdrop.addEventListener('click', e=>{
            removeBackdrop(e.target);
            removeform(form);
        })
    })
})


btn.addEventListener('click', e => {
    let data = e.target.classList.contains('add')? 'add' : 'browse';
    if(data==='add'){
        btn.classList.add('browse');
        btn.classList.remove('add');
        btn.innerHTML = '<i class="fa fa-fw fa-globe"></i> Browse User';
        div.innerHTML = '<i class="fa fa-fw fa-plus"></i> Add User'
        form.classList.remove('d-none');
        table.classList.add('d-none');
        
    }else if(data==='browse'){
        btn.classList.add('add');
        btn.classList.remove('browse');
        btn.innerHTML = '<i class="fa fa-fw fa-plus"></i> Add User';
        div.innerHTML = '<i class="fa fa-fw fa-globe"></i> Browse User'
        form.classList.add('d-none');
        table.classList.remove('d-none');
    }
})

function getEditData(id){
    let form_data = new FormData();
    let xmlhttp = new XMLHttpRequest();
    form_data.append('edit-id', id);
    xmlhttp.open('POST', "./includes/edit.php");
    xmlhttp.send(form_data);
    xmlhttp.onreadystatechange = function() {
        if(xmlhttp.readyState == 4 && xmlhttp.status==200) {
            let response = xmlhttp.responseText;
            let result = JSON.parse(response);
            insertToForm(result);
        }
    }
}

function insertToForm(data) {
    let obj = {
        id : document.getElementById('id'),
        name : document.getElementById('name'),
        email: document.getElementById('email'),
        mobile : document.getElementById('mobile'),
        submit : document.getElementById('submit'),
    };

    obj.id.parentElement.classList.remove("d-none");
    obj.id.value = data.id;
    obj.name.value = data.name;
    obj.email.value = data.email;
    obj.mobile.value = data.mobile;

    submit.addEventListener('click', e=>{
        e.preventDefault();
        e.target.disabled = true;
        let form_data = new FormData();
        for (const key in obj) {
            form_data.append(obj[key].name, obj[key].value);
        }
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.open('POST', "./includes/edit.php");
        xmlhttp.send(form_data);
        xmlhttp.onreadystatechange = function() {
            if(xmlhttp.readyState == 4 && xmlhttp.status==200) {
                if(xmlhttp.responseText==='Updated'){
                    e.target.disabled = false;
                    obj.id.parentElement.classList.add("d-none");
                    document.querySelector(".backdrop").click();
                }
            }
        }
    })
}