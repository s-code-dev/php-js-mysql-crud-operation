let usersInf = document.getElementsByClassName('card-user');
 console.log('dcsdc');

for(let i = 0; i < usersInf.length; i++){

  usersInf[i].addEventListener('click', function(event){
    let item = event.target.id;
 

    // Выполнение POST-запроса
    fetch('/js/edit-inf/edit-server.php', {
        method: 'POST', // Указываем метод
        body: item // Преобразуем объект в JSON-строку
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text(); 
    })
    .then(data => {
       if(data){

        event.target.disabled = true;
        document.querySelector('.wrapper-main').insertAdjacentHTML('beforeend', data );
        closedBlockEdit(event.target.id);
        

       }
       
    })
    .catch((error) => {
        console.log(error); // Обработка ошибок
    });

  })
}
function closedBlockEdit(id){
    let form = document.getElementsByClassName('remove-form');
    let wrapperForm = document.getElementsByClassName('base-style-form');
   

      for(let i = 0; i < form.length; i++){
        form[i].addEventListener('click', function(event){

          wrapperForm[i].remove();
          disabledBotton(id);
          

        })

      }
}

function disabledBotton(id){
    let bottonEdit = document.querySelector(`.button-${id}`);
    bottonEdit.disabled = false;

}