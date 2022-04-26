const xhr = new XMLHttpRequest()

let li = document.querySelectorAll(".tree div")
for (let liElement of li) {
    liElement.addEventListener("click", viewList, false)
}

let removeButton = document.querySelector(".removeFirst")
if(removeButton!=null) removeButton.addEventListener("click", remove, false)

let editButton = document.querySelector(".edit")
if(editButton!=null) editButton.addEventListener("click", edit, false)

let addButton = document.querySelector(".addFirst")
if(addButton!=null) addButton.addEventListener("click", addDatabase, false)

function viewList(){
    if(this.parentElement.lastChild.tagName!=="UL"){
        xhr.open('POST', 'index.php?action=ajax')
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.send('id=' + this.dataset.id)

        xhr.onload  = () => {
            if(xhr.status === 200){
                let reslut = JSON.parse(xhr.response)
                if(reslut.length>0){
                    // Tworzenie listy
                    let ul = document.createElement("ul")
                    ul.dataset.id_rodzic=this.dataset.id
                    for(let e of reslut){
                        //Tworzenie elemntu
                        let li = document.createElement("li")
                        let div = document.createElement("div")
                        div.addEventListener("click", viewList, false)
                        div.dataset.id = e.id
                        div.classList.add("list")
                        div.innerText = e.nazwa

                        li.append(div)
                        li.append(newElemRemove())
                        li.append(createEditButton())
                        ul.append(li)
                    }
                    //dodawania do listy
                    ul.append(createAddButton())
                    ul.dataset.have=true
                    this.parentElement.append(ul)
                }
                else {
                    let ul = document.createElement("ul")
                    ul.dataset.id_rodzic=this.dataset.id
                    ul.append(createAddButton())
                    ul.dataset.have=true
                    this.parentElement.append(ul)
                }
            }
        }
    }
    else {
        if (this.parentElement.lastChild.style.display === "none") this.parentElement.lastChild.style.display = "flex"
        else this.parentElement.lastChild.style.display = "none"
    }
}
//Tworzenie przysisku do dodawania
function createAddButton(){
    let li = document.createElement("li")
    let button = document.createElement("button")
    button.className += "add btn btn-secondary"
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
    div.addEventListener('click', viewList, false)
    div.classList.add("list")
    return div;
}

function newElemRemove(){
    let button = document.createElement("button")
    button.className+="remove btn btn-danger m-2"
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

        let id
        let id_prev
        if(!this.classList.contains("addFirst")){

            id = this.parentElement.parentElement.dataset.id_rodzic
            if(this.parentElement.parentElement.children.length-2>=0){
                id_prev = this.parentElement.parentElement.children[this.parentElement.parentElement.children.length-2].firstElementChild.dataset.id
            }
            else id_prev = null
        }
        else {
            id= null
            id_prev = null
        }

        if(!this.classList.contains("addFirst")){
            let button = createAddButton()
            this.parentElement.parentElement.append(button)
        }
        this.remove();

        xhr.open('POST', 'index.php?action=add')
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.send('nazwa=' + div.textContent + "&id_rodzic=" +id+ "&id_prev=" +id_prev)

        xhr.onload = () => {
          if(xhr.status===200 && xhr.readyState === 4){
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
            let button = document.createElement("button")
            button.classList.add("addFirst")
            button.className+= " btn btn-secondary"
            button.innerText="+"
            button.addEventListener("click", addDatabase, false)
            li.append(button);
            document.querySelector(".tree ul").append(li)
        }
        xhr.onload  = () =>{
            if(xhr.status=== 200) this.parentElement.remove()
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

    if(preElement!=null) preElement.style.borderColor = 'black'

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

    document.querySelector("input[name=id_rodzic]").value = parent.dataset.id_rodzic
    document.querySelector("input[name=id_prev]").value = parent.children[parent.children.length-2].firstElementChild.dataset.id
    if(li.nextElementSibling.firstElementChild.tagName==="DIV"){
        document.querySelector("input[name=id_next]").value = li.nextElementSibling.firstElementChild.dataset.id
    }

    document.forms[0].submit();

}, false)

document.getElementById("down").addEventListener("click", function (){
    let li = preElement.parentElement
    let ul = li.previousElementSibling.lastElementChild

    if(ul.tagName==="UL"){
        document.querySelector("input[name=id_rodzic]").value = ul.dataset.id_rodzic
        if(ul.children[ul.children.length-2]!=null) document.querySelector("input[name=id_prev]").value = ul.children[ul.children.length-2].firstElementChild.dataset.id
        else document.querySelector("input[name=id_prev]").value = -1
        if(li.nextElementSibling.firstElementChild.tagName==="DIV"){
            document.querySelector("input[name=id_next]").value = li.nextElementSibling.firstElementChild.dataset.id
        }
        document.forms[0].submit();
    }
    else alert("Aby przenieść otworz gałąż elementu z przodu")
}, false)

document.getElementById("left").addEventListener("click", function (){
    let li =  preElement.parentElement
    if(li.previousElementSibling!=null){
        if(li.nextElementSibling.firstElementChild.tagName==="DIV") document.querySelector("input[name=id_n]").value = li.nextElementSibling.firstElementChild.dataset.id
        li.parentElement.insertBefore(li, li.previousElementSibling)

        document.querySelector("input[name=id_next]").value = li.nextElementSibling.firstElementChild.dataset.id
        if(li.previousElementSibling==null) document.querySelector("input[name=id_prev]").value = -1
        else document.querySelector("input[name=id_prev]").value = li.previousElementSibling.firstElementChild.dataset.id

        document.forms[0].submit()
    }

}, false)

document.getElementById("right").addEventListener("click", function (){
    let li =  preElement.parentElement

    if(li.nextElementSibling.firstElementChild.tagName==="DIV"){
        if(li.nextElementSibling.firstElementChild.tagName==="DIV") document.querySelector("input[name=id_n]").value = li.nextElementSibling.firstElementChild.dataset.id
        if(li.previousElementSibling==null) document.querySelector("input[name=id_r]").value = -1
        else document.querySelector("input[name=id_r]").value = li.previousElementSibling.firstElementChild.dataset.id


        li.nextElementSibling.after(li)

        document.querySelector("input[name=id_prev]").value = li.previousElementSibling.firstElementChild.dataset.id

        if(li.nextElementSibling.firstElementChild.tagName==="DIV") document.querySelector("input[name=id_next]").value = li.nextElementSibling.firstElementChild.dataset.id

        if(li.previousElementSibling==null) document.querySelector("input[name=id_prev]").value = -1
        else document.querySelector("input[name=id_prev]").value = li.previousElementSibling.firstElementChild.dataset.id

        document.forms[0].submit()
    }

}, false)