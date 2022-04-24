const xhr = new XMLHttpRequest();

let li = document.querySelectorAll(".tree li");
for (let liElement of li) {
    liElement.addEventListener("click", viewList, false)
}

let removeButton = document.querySelector(".removeFirst");
if(removeButton!=null) removeButton.addEventListener("click", remove, false)

let editButton = document.querySelector(".edit");
if(editButton!=null) editButton.addEventListener("click", edit, false)

let addButton = document.querySelector(".addFirst");
if(addButton!=null) addButton.addEventListener("click", addDatabase, false)

function viewList(e){
    if(this.lastChild.tagName!="UL" || this.lastChild==undefined){
        xhr.open('POST', 'index.php?action=ajax');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('id=' + this.firstElementChild.dataset.id)

        xhr.onload = () => {
            if(xhr.status == 200){
                let reslut = JSON.parse(xhr.response);
                if(reslut.length>0){
                    // Tworzenie listy
                    let ul = document.createElement("ul");
                    ul.dataset.id_rodzic=this.children[0].dataset.id;
                    for(let e of reslut){
                        //Tworzenie elemntu
                        let li = document.createElement("li");
                        li.addEventListener("click", viewList, false);
                        let div = document.createElement("div");
                        div.dataset.id = e.id;
                        div.classList.add("list");
                        div.innerText = e.nazwa;

                        li.append(div);
                        li.append(newElemRemove());
                        li.append(createEditButton())
                        ul.append(li);
                    }
                    //Możliwośc dodawania do listy
                    ul.append(createAddButton())
                    ul.dataset.have=true;
                    this.append(ul);
                }
                else {
                    let ul = document.createElement("ul");
                    ul.dataset.id_rodzic=this.firstElementChild.dataset.id;
                    ul.append(createAddButton())
                    ul.dataset.have=true;
                    this.append(ul);
                }
            }
        }
    }
    else {
        if (this.lastChild.style.display === "none") this.lastChild.style.display = "flex"
        else this.lastChild.style.display = "none";
    }
    e.stopPropagation();
}
//Tworzenie przysisku do dodawania
function createAddButton(){
    let li = document.createElement("li");
    li.addEventListener('click', viewList, false)
    let button = document.createElement("button");
    button.classList.add("add")
    button.innerText="+"
    button.addEventListener("click", addDatabase, false)
    li.append(button);
    return li;
}
//Tworzenie nowego elemntu
function newlist(){
    let div = document.createElement("div")
    div.textContent = prompt("Nazwa:");
    if(div.textContent==null) return null
    div.classList.add("list");
    return div;
}

function newElemRemove(){
    let button = document.createElement("button")
    button.classList.add("remove");
    button.innerText = "Usuń";
    button.addEventListener("click", remove, false);
    return button;
}

//Dodwanie do bazdy
function addDatabase(e){
    this.after(createEditButton());
    let remove = newElemRemove()
    if(this.className=="addFirst") remove.className="removeFirst"
    this.after(remove)
    let div = newlist()
    this.after(div)

    let id = this.parentElement.parentElement.dataset.id_rodzic
    let id_prev
    if(this.parentElement.parentElement.children.length-2>=0){
        id_prev = this.parentElement.parentElement.children[this.parentElement.parentElement.children.length-2].firstElementChild.dataset.id
    }
    else id_prev = null
    if(this.className!="addFirst"){
        let button = createAddButton();
        this.parentElement.parentElement.append(button)
    }
    this.remove();

    xhr.open('POST', 'index.php?action=add');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('nazwa=' + div.textContent + "&id_rodzic=" +id+ "&id_prev=" +id_prev)

    xhr.onload = () => {
      if(xhr.status==200){
          div.dataset.id=xhr.response;
      }
    }
    e.stopPropagation()
}

function remove(e){
    if(confirm("Czy na pewno usunąć?")){
        xhr.open('POST', 'index.php?action=remove');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('id=' + this.previousSibling.dataset.id)
        if(this.className=="removeFirst"){
            let li = document.createElement("li");
            li.addEventListener('click', viewList, false)
            let button = document.createElement("button");
            button.classList.add("addFirst")
            button.innerText="+"
            button.addEventListener("click", addDatabase, false)
            li.append(button);
            document.querySelector(".tree ul").append(li)
        }
        xhr.onload = () =>{
            if(xhr.status== 200) this.parentElement.remove();
        }
    }
    e.stopPropagation()
}

function createEditButton(){
    let button = document.createElement("button")
    button.textContent="Edytuj";
    button.addEventListener("click", edit, false);
    return button;
}

let preElement;
function edit(e){

    if(preElement!=null){
        if( document.querySelector("input[type=text]").value!=preElement.textContent ||
            document.querySelector("input[name=id_rodzic]").value != preElement.parentElement.parentElement.dataset.id_rodzic ||
            document.querySelector("input[name=id_prev]").value != preElement.parentElement.previousElementSibling.firstElementChild.dataset.id){
            alert("Zmiany nie zostaną zapisane")
        }
        preElement.style.borderColor = 'salmon'
    }

    let elem = this.parentElement.firstElementChild;
    elem.style.borderColor = 'red'
    document.querySelector("input[type=text]").value = elem.textContent
    document.querySelector("input[name=id]").value= elem.dataset.id
    preElement = elem
    e.preventDefault()
}

document.getElementById("up").addEventListener("click", function (){
    let li =  preElement.parentElement;
    let parent = preElement.parentElement.parentElement.parentElement.parentElement;
    if(parent.dataset.id_rodzic != undefined){
        parent.insertBefore(li, parent.lastChild);
        document.querySelector("input[name=id_rodzic]").value = parent.dataset.id_rodzic
        document.querySelector("input[name=id_prev]").value = parent.children[parent.children.length-3].firstElementChild.dataset.id
    }

}, false);

document.getElementById("down").addEventListener("click", function (){

    let li = preElement.parentElement;
    let ul = preElement.parentElement.parentElement.firstElementChild.lastElementChild

    if(ul.tagName=="UL"){
        ul.insertBefore(li, ul.lastChild)
        document.querySelector("input[name=id_rodzic]").value = ul.dataset.id_rodzic
        document.querySelector("input[name=id_prev]").value = ul.children[ul.children.length-3].firstElementChild.dataset.id
    }

}, false)

document.getElementById("left").addEventListener("click", function (){

}, false)

document.getElementById("right").addEventListener("click", function (){

}, false)