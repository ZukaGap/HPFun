console.clear();

const dropdowns = document.querySelectorAll('.dropdown');

dropdowns.forEach(dropdown => {
dropdown.addEventListener('click', (e) => {
    dropdown.classList.toggle('dropdown__options--visible')
})

dropdown.querySelectorAll('.dropdown__options .dropdown__option').forEach(opt => {
    opt.addEventListener('click', (e) => {      
    dropdown.querySelector('.dropdown__selected').innerHTML = opt.innerHTML;
    })
})
})