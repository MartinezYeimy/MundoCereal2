let listElements = document.querySelectorAll('.list__button--click');

const toggleButton = document.getElementById('toggle-menu');
const nav = document.querySelector('.nav');

toggleButton.addEventListener('click', () => {
    nav.classList.toggle('collapsed');
});

listElements.forEach(listElement => {
    listElement.addEventListener('click', ()=>{
        
        listElement.classList.toggle('arrow');

        let height = 0;
        let menu = listElement.nextElementSibling;
        if(menu.clientHeight == "0"){
            height=menu.scrollHeight;
        }

        menu.style.height = `${height}px`;

    })
});