const xhr = new XMLHttpRequest()

let li = document.querySelectorAll(".tree li")
for (let liElement of li) {
    liElement.addEventListener("click", viewList, false)
}

let removeButton = document.querySelector(".removeFirst")
if(removeButton!=null) removeButton.addEventListener("click", remove, false)

let editButton = document.querySelector(".edit")
if(editButton!=null) editButton.addEventListener("click", edit, false)

let addButton = document.querySelector(".addFirst")
if(addButton!=null) addButton.addEventListener("click", addDatabase, false)

function viewList(e){
    if(this.lastChild.tagName!="UL" || this.lastChild==undefined){
        xhr.open('POST', 'index.php?action=ajax')
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.send('id=' + this.firstElementChild.dataset.id)

        xhr.onload = () => {
            if(xhr.status == 200){
                let reslut = JSON.parse(xhr.response)
                if(reslut.length>0){
                    // Tworzenie listy
                    let ul = document.createElement("ul")
                    ul.dataset.id_rodzic=this.children[0].dataset.id
                    for(let e of reslut){
                        //Tworzenie elemntu
                        let li = document.createElement("li")
                        li.addEventListener("click", viewList, false)
                        let div = document.createElement("div")
                        div.dataset.id = e.id
                        div.classList.add("list")
                        div.innerText = e.nazwa

                        li.append(div)
                        li.append(newElemRemove())
                        li.append(createEditButton())
                        ul.append(li)
                    }
                    //Możliwośc dodawania do listy
                    ul.append(createAddButton())
                    ul.dataset.have=true
                    this.append(ul)
                }
                else {
                    let ul = document.createElement("ul")
                    ul.dataset.id_rodzic=this.firstElementChild.dataset.id
                    ul.append(createAddButton())
                    ul.dataset.have=true
                    this.append(ul)
                }
            }
        }
    }
    else {
        if (this.lastChild.style.display === "none") this.lastChild.style.display = "flex"
        else this.lastChild.style.display = "none"
    }
    e.stopPropagation();
}
//Tworzenie przysisku do dodawania
function createAddButton(){
    let li = document.createElement("li")
    li.addEventListener('click', viewList, false)
    let button = document.createElement("button")
    button.classList.add("add")
    button.className += " btn btn-secondary"
    button.innerText="+"
    button.addEventListener("click", addDatabase, false)
    li.append(button)
    return li
}
//Tworzenie nowego elemntu
function newlist(){
    let div = document.createElement("div")
    div.textContent = prompt("Nazwa:")
    if(div.textContent==='') return null
    div.classList.add("list")
    return div;
}

function newElemRemove(){
    let button = document.createElement("button")
    button.classList.add("remove")
    button.className+=" btn btn-danger m-2"
    button.innerText = "Usuń"
    button.addEventListener("click", remove, false)
    return button;
}

//Dodwanie do bazdy
function addDatabase(e){
    let div = newlist()
    if(div!=null){
        this.after(createEditButton())
        let remove = newElemRemove()
        if(this.classList.contains("addFirst")) remove.className="removeFirst btn btn-danger m-2"
        this.after(remove)
        this.after(div)

        let id = this.parentElement.parentElement.dataset.id_rodzic
        let id_prev
        if(this.parentElement.parentElement.children.length-2>=0){
            id_prev = this.parentElement.parentElement.children[this.parentElement.parentElement.children.length-2].firstElementChild.dataset.id
        }
        else id_prev = null
        if(!this.classList.contains("addFirst")){
            let button = createAddButton()
            this.parentElement.parentElement.append(button)
        }
        this.remove();

        xhr.open('POST', 'index.php?action=add')
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.send('nazwa=' + div.textContent + "&id_rodzic=" +id+ "&id_prev=" +id_prev)

        xhr.onload = () => {
          if(xhr.status==200){
              div.dataset.id=xhr.response
          }
        }
    }
    e.stopPropagation()
}

function remove(e){
    if(confirm("Czy na pewno usunąć?")){
        xhr.open('POST', 'index.php?action=remove')
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.send('id=' + this.previousSibling.dataset.id)
        if(this.classList.contains("removeFirst")){
            let li = document.createElement("li")
            li.addEventListener('click', viewList, false)
            let button = document.createElement("button")
            button.classList.add("addFirst")
            button.className+= " btn btn-secondary"
            button.innerText="+"
            button.addEventListener("click", addDatabase, false)
            li.append(button);
            document.querySelector(".tree ul").append(li)
        }
        xhr.onload = () =>{
            if(xhr.status== 200) this.parentElement.remove()
        }
    }
    e.stopPropagation()
}

function createEditButton(){
    let button = document.createElement("button")
    button.textContent="Edytuj"
    button.className="btn btn-success"
    button.addEventListener("click", edit, false)
    return button;
}

let preElement;
function edit(e){
    document.forms[0].firstElementChild.disabled = false

    if(preElement!=null){
        if( document.querySelector("input[type=text]").value!=preElement.textContent ||
            document.querySelector("input[name=id_rodzic]").value != preElement.parentElement.parentElement.dataset.id_rodzic ||
            document.querySelector("input[name=id_prev]").value != preElement.parentElement.previousElementSibling.firstElementChild.dataset.id){
            alert("Zmiany nie zostaną zapisane")
            window.location.reload(true)
        }
        preElement.style.borderColor = 'black'
    }

    let elem = this.parentElement.firstElementChild
    elem.style.borderColor = 'red'
    document.querySelector("input[type=text]").value = elem.textContent
    document.querySelector("input[name=id]").value= elem.dataset.id
    preElement = elem
    e.stopPropagation()
}

document.getElementById("up").addEventListener("click", function (){
    let li =  preElement.parentElement
    let parent = li.parentElement.parentElement.parentElement
    if(parent.dataset.id_rodzic != undefined){
        if(li.nextElementSibling.firstElementChild.tagName==="DIV"){
            let id_prev
            if(li.previousElementSibling===null) id_prev=0
            else id_prev=li.previousElementSibling.firstElementChild.dataset.id

            xhr.open('POST', 'index.php?action=up')
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
            xhr.send('id=' + li.nextElementSibling.firstElementChild.dataset.id + "&id_prev=" + id_prev)

            xhr.onload = () =>{
                if(xhr.status== 200) alert(xhr.response)
            }
        }

        parent.insertBefore(li, parent.lastChild)

        document.querySelector("input[name=id_rodzic]").value = parent.dataset.id_rodzic
        document.querySelector("input[name=id_prev]").value = parent.children[parent.children.length-3].firstElementChild.dataset.id
        document.querySelector("input[name=id_next]").value = null
    }

}, false)

document.getElementById("down").addEventListener("click", function (){

    let li = preElement.parentElement
    if(li.previousElementSibling!=null){
        let ul = li.previousElementSibling.lastElementChild

        if(ul.tagName==="UL"){
            if(ul.style.display === "none") ul.style.display="flex"
            ul.insertBefore(li, ul.lastChild)
            document.querySelector("input[name=id_rodzic]").value = ul.dataset.id_rodzic
            document.querySelector("input[name=id_prev]").value = ul.children[ul.children.length-3].firstElementChild.dataset.id
            document.querySelector("input[name=id_next]").value = null
        }
        else alert("Aby przenieść otworz gałąż elementu z przodu")
    }

}, false)

document.getElementById("left").addEventListener("click", function (){
    let li =  preElement.parentElement
    if(li.previousElementSibling!=null){
        if(li.previousElementSibling.firstElementChild.tagName==="DIV"){
            li.parentElement.insertBefore(li, li.previousElementSibling)
            if(li.previousElementSibling==undefined) document.querySelector("input[name=id_prev]").value = 0
            else document.querySelector("input[name=id_prev]").value = li.previousElementSibling.firstElementChild.dataset.id

            document.querySelector("input[name=id_next]").value = li.nextElementSibling.firstElementChild.dataset.id
        }
    }

}, false)

document.getElementById("right").addEventListener("click", function (){
    let li =  preElement.parentElement
    if(li.nextElementSibling!=null){
        if(li.nextElementSibling.firstElementChild.tagName==="DIV"){
            li.nextElementSibling.after(li)
            document.querySelector("input[name=id_prev]").value = li.previousElementSibling.firstElementChild.dataset.id
            document.querySelector("input[name=id_next]").value = li.nextElementSibling.firstElementChild.dataset.id

            if(li.nextElementSibling.firstElementChild.tagName!=="DIV") document.querySelector("input[name=id_next]").value = 0
        }
    }
}, false)