let fill;

document.getElementById("buttonAdd").addEventListener("click", function(){
    let category = document.getElementById("name").value;
    fill = document.createElement("div");
    fill.draggable=true;
    fill.classList.add("fill");
    fill.textContent=category;

    fill.addEventListener('dragstart', dragStart);
    fill.addEventListener('dragend', dragEnd);

    document.getElementById("formAdd").after(fill);
}, false)

const tree = document.querySelectorAll('.empty, .add');

const empties = document.querySelectorAll(".empty");

for (const e of empties) {
    e.addEventListener('click', function (){

        let xhr = new XMLHttpRequest();

        xhr.onload =  () =>{
            if(xhr.status === 200){
                //let result = JSON.parse(xhr.responseText);

                //alert(result);

                alert(xhr.responseText);
            }
        }
        const json = {"id": this.dataset.id};

        xhr.open('POST', 'data.php', );
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(json))
    });

}

for (const e of tree) {
    e.addEventListener('dragover', dragOver);
    e.addEventListener('dragenter', dragEnter);
    e.addEventListener('dragleave', dragLeave);
    e.addEventListener('drop', dragDrop);
}

function dragStart() {
    this.className += ' hold';
    setTimeout(() => (this.className = 'invisible'), 0);
}

function dragEnd() {
    this.className = 'fill';
}

function dragOver(e) {
    e.preventDefault();
}

function dragEnter(e) {
    e.preventDefault();
    this.className += ' hovered';
}

function dragLeave() {
    this.className = 'empty';
}

function dragDrop() {
    this.className = 'empty';
    this.append(fill);
}

let ul = document.querySelectorAll(".tree ul");
let ult = document.querySelectorAll(".tree> ul");

for (let ulElement of ul) {
    ulElement.style.display="none";
}

for (let ulElement of ult) {
    ulElement.style.display="flex";
}

let li = document.querySelectorAll(".tree li");
for (let liElement of li) {
    liElement.addEventListener("click", function (e){
        for(let l of this.children){
            if(l.tagName=="UL"){
                if(l.style.display=="none") l.style.display="flex";
                else l.style.display="none";
            }
        }
        e.stopPropagation();
    }, false)
}