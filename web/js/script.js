$(document).ready(function(){
	
	$('.comment .delete').click(function(){
		alert("!!!");
		var elem = $(this).closest('.comment');
		
		$.confirm({
			'title'		: 'Подтверждение удаления',
			'message'	: 'Вы решили удалить пункт. <br />После удаления его нельзя будет восстановить! Продолжаем?',
			'buttons'	: {
				'Да'	: {
					'class'	: 'blue',
					'action': function(){
						elem.slideUp();
					}
				},
				'Нет'	: {
					'class'	: 'gray',
					'action': function(){}	// В данном случае ничего не делаем. Данную опцию можно просто опустить.
				}
			}
		});
		
	});
	
});