const tree = document.querySelectorAll('.empty, .add');

let ul = document.querySelectorAll(".tree ul");
let ult = document.querySelectorAll(".tree> ul");
/*
for (let ulElement of ul) {
    ulElement.style.display="none";
}

for (let ulElement of ult) {
    ulElement.style.display="flex";
}*/

function downloadList(){

}


function addList(e) {
    if (this.dataset.c == null) {
        let xhr = new XMLHttpRequest();

        xhr.onload = () => {
            if (xhr.status === 200) {

                let result = JSON.parse(xhr.responseText);

                let ul = document.createElement("ul");
                if (result.length>0) {
                    for (let e of result) {
                        let li = document.createElement("li");
                        let liAdd = document.createElement("li");
                        let divAdd = document.createElement("button")
                        divAdd.addEventListener("click", function (e){
                            let div = document.createElement("div");
                            div.classList.add("empty");
                            div.textContent = prompt("Podaj nazwę");
                            div.addEventListener("click", addList, false);

                            this.after(div)
                            this.remove();
                            e.stopPropagation();
                        }, false);
                        divAdd.innerText = "+";
                        liAdd.append(divAdd);

                        li.addEventListener("click", addList, false);
                        let empty = document.createElement("div");
                        empty.dataset.id = e.id;
                        empty.classList.add("empty");
                        empty.innerText = e.nazwa;

                        let remove = document.createElement("div");
                        remove.classList.add("remove");
                        remove.innerText = "remove";
                        li.append(empty);
                        li.append(remove);
                        ul.append(li);
                        ul.append(liAdd);
                    }
                    ul.append()
                    ul.style.display = "flex";
                    this.append(ul);
                    this.dataset.c = true;
                }
                if(result.length==0){
                    let ul = document.createElement("ul");

                    let liAdd = document.createElement("li");
                    let divAdd = document.createElement("button")
                    divAdd.addEventListener("click", function (e){
                        let div = document.createElement("div");
                        div.classList.add("empty");
                        div.textContent = prompt("Podaj nazwę");
                        div.addEventListener("click", addList, false);

                        this.after(div)
                        this.remove();
                        e.stopPropagation();
                    }, false);
                    divAdd.innerText = "+";
                    liAdd.append(divAdd);

                    ul.append(liAdd);
                    this.append(ul);
                    this.dataset.c = true;
                }
            }
        }
        const json = {"id": this.dataset.id};

        xhr.open('POST', 'index.php?action=ajax');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('id=' + this.children[0].dataset.id)
    }
    if (this.dataset.c == "true") {
        if (this.lastChild.style.display === "none") this.lastChild.style.display = "flex"
        else this.lastChild.style.display = "none";
    }
    e.stopPropagation();
}

/*
    for(let l of this.children){
        if(l.tagName=="UL"){
            if(l.style.display=="none"){
                    let xhr = new XMLHttpRequest();

                    xhr.onload = () => {
                        if (xhr.status === 200) {

                            let result = JSON.parse(xhr.responseText);

                            let ul = document.createElement("ul");

                            for (let e of result) {
                                let li = document.createElement("li");
                                let empty = document.createElement("div");
                                empty.dataset.id = e.id;
                                empty.classList.add("empty");
                                empty.innerText = e.nazwa;

                                let remove = document.createElement("div");
                                remove.classList.add("remove");
                                remove.innerText = "remove";
                                li.append(empty);
                                li.append(remove);
                                ul.append(li);
                            }
                            this.nextSibling.after(ul);
                        }
                    }
                    const json = {"id": this.dataset.id};

                    xhr.open('POST', 'index.php?action=ajax');
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('id=' + this.dataset.id)
                    l.style.display="flex";
            }
            else l.style.display="none";
        }
    }
    e.stopPropagation();
}*/

let li = document.querySelectorAll(".tree li");
for (let liElement of li) {
    liElement.addEventListener("click", addList, false)
}