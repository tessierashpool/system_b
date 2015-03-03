$(document).ready(function() {
	var lastId = 0;
	$('#images_off_on_val').val('1');
	$('#gif_off_on_val').val('1');
	$('#browse_btn').click(function(){
		$('#file-field').trigger('click');  
	});
	$('#upload_d').click(function(){
		$('.upload_d_cont').show('200');  
	});	
	$('.upload_cont_closw').click(function(){
		$('.upload_d_cont').hide('200');  
	});		
	size_of_divs();
	$(window).resize(function(){
		 size_of_divs();
	
	});	
	var button_enable = 1;
	$('#reload_btn').click(function(){
		if(button_enable == 1)
		{
			button_enable = 0;
			getRandImg();
		}
	})
	$('#prev_btn').click(function(){
		if(button_enable == 1)
		{
			button_enable = 0;
			getPrevImg();
		}
	})
	$('#next_btn').click(function(){
		if(button_enable == 1)
		{
			button_enable = 0;
			getNextImg();
		}
	})	
	
	$('#gif_off_on').click(function(){
		if($('#gif_off_on_val').val()=='1')
		{
			$(this).css('background-position','-65px 0');
			$('#gif_off_on_val').val('0');
			if($('#images_off_on_val').val()=='0')
			{
				$('#images_off_on').css('background-position','0 0');
				$('#images_off_on_val').val('1');
			}
		}	
		else
		{
			$(this).css('background-position','0 0');
			$('#gif_off_on_val').val('1');
		}
		itemsCount();
	})
	
	$('#images_off_on').click(function(){
		if($('#images_off_on_val').val()=='1')
		{
			$(this).css('background-position','-65px 0');
			$('#images_off_on_val').val('0');
			if($('#gif_off_on_val').val()=='0')
			{
				$('#gif_off_on').css('background-position','0 0');
				$('#gif_off_on_val').val('1');			
			}
		}	
		else
		{
			$(this).css('background-position','0 0');
			$('#images_off_on_val').val('1');
		}
		itemsCount();
	})
	
	function itemsCount(){
		if($('#gif_off_on_val').val()=='1'&&$('#images_off_on_val').val()=='1')
			$('#items_count').text(all_items_count+' items ');
		else if($('#gif_off_on_val').val()=='1'&&$('#images_off_on_val').val()=='0')	
			$('#items_count').text(gifs_count+' gifs ');
		else if($('#gif_off_on_val').val()=='0'&&$('#images_off_on_val').val()=='1')	
			$('#items_count').text(images_count+' images ');
	}
	
    // Консоль
    var $console = $("#console");

    // Инфа о выбранных файлах
    var countInfo = $("#info-count");
    var sizeInfo = $("#info-size");

    // ul-список, содержащий миниатюрки выбранных файлов
    var imgList = $('#img-list');

    // Контейнер, куда можно помещать файлы методом drag and drop
    var dropBox = $('#img-container');

    // Счетчик всех выбранных файлов и их размера
    var imgCount = 0;
    var imgSize = 0;


    // Стандарный input для файлов
    var fileInput = $('#file-field');

    // Тестовый canvas


    ////////////////////////////////////////////////////////////////////////////
    // Подключаем и настраиваем плагин загрузки

    fileInput.damnUploader({
        // куда отправлять
        url: './serverL.php',
        // имитация имени поля с файлом (будет ключом в $_FILES, если используется PHP)
        fieldName:  'my-pic',
        // дополнительно: элемент, на который можно перетащить файлы (либо объект jQuery, либо селектор)
        dropBox: dropBox,
        // максимальное кол-во выбранных файлов (если не указано - без ограничений)
        limit: 14,
        // когда максимальное кол-во достигнуто (вызывается при каждой попытке добавить еще файлы)
        onLimitExceeded: function() {
            log('Допустимое кол-во файлов уже выбрано');
        },
        // ручная обработка события выбора файла (в случае, если выбрано несколько, будет вызвано для каждого)
        // если обработчик возвращает true, файлы добавляются в очередь автоматически
        onSelect: function(file) {
            addFileToQueue(file);
            return false;
        },
        // когда все загружены
        onAllComplete: function() {
            log('<span style="color: blue;">*** Все загрузки завершены! ***</span>');
            imgCount = 0;
            imgSize = 0;
            updateInfo();
        }
    });



    ////////////////////////////////////////////////////////////////////////////
    // Вспомогательные функции

    // Вывод в консоль
    function log(str) {
        $('<p/>').html(str).prependTo($console);
    }

    // Вывод инфы о выбранных
    function updateInfo() {
        countInfo.text( (imgCount == 0) ? 'Изображений не выбрано' : ('Изображений выбрано: '+imgCount));
        sizeInfo.text( (imgSize == 0) ? '-' : Math.round(imgSize / 1024));
    }

    // Обновление progress bar'а
    function updateProgress(bar, value) {
        var width = bar.width();
        var bgrValue = -width + (value * (width / 100));
        bar.attr('rel', value).css('background-position', bgrValue+'px center').text(value+'%');
    }


    // Отображение выбраных файлов, создание миниатюр и ручное добавление в очередь загрузки.
    function addFileToQueue(file) {
		dropBox.removeClass('highlighted');
        // Создаем элемент li и помещаем в него название, миниатюру и progress bar
        var li = $('<li/>').addClass('item_progres_line').appendTo(imgList);
		var wrap = $('<div/>').addClass('wrap').appendTo(li);
		var maxL=20;
		var str=file.name;
		if(str.length > maxL)
		{
			var firsPart=str.substr(0,maxL)+'...';	
			var secPart=str.substr(-5);	
			var cutTitle = firsPart + secPart;
		}
		else
		{
			var cutTitle=str;
		}	
        var title = $('<div/>').addClass('title_it').text(cutTitle+' ').appendTo(wrap);
		var cancelButtonDiv = $('<div/>').addClass('del_it_cont').appendTo(wrap);
        var cancelButton = $('<a/>').attr({
            href: '#cancel',
            title: 'отменить',
			class: 'del_btn'
        }).appendTo(cancelButtonDiv);

        // Если браузер поддерживает выбор файлов (иначе передается специальный параметр fake,
        // обозночающий, что переданный параметр на самом деле лишь имитация настоящего File)
	   if(!file.fake) {

            // Отсеиваем не картинки
            //var imageType = /image.*/;
			var imageType = /gif|png|jpg|jpeg/;
            if (!file.type.match(imageType)) {
                log('Файл отсеян: `'+file.name+'` (тип '+file.type+')');
				li.remove();
                return true;
            }

            if (file.size>6291456) {
                log('Файл отсеян: `'+file.name+'` (тип '+file.type+')');
				li.remove();
                return true;
            }
            // Добавляем картинку и прогрессбар в текущий элемент списка
            //var img = $('<img/>').appendTo(li);
			var progressDiv = $('<div/>').addClass('progress_b_cont').appendTo(wrap);
            var pBar = $('<div/>').addClass('progress_b').attr('rel', '0').text('0%').appendTo(progressDiv);
			var sucsDiv = $('<div/>').addClass('upl_sucs_cont').appendTo(wrap);
            // Создаем объект FileReader и по завершении чтения файла, отображаем миниатюру и обновляем
            // инфу обо всех файлах (только в браузерах, подерживающих FileReader)
            /*if($.support.fileReading) {
                var reader = new FileReader();
                reader.onload = (function(aImg) {
                    return function(e) {
                        aImg.attr('src', e.target.result);
                        aImg.attr('width', 150);
                    };
                })(img);
                reader.readAsDataURL(file);
            }*/

            log('Картинка добавлена: `'+file.name + '` (' +Math.round(file.size / 1024) + ' Кб)');
            imgSize += file.size;
        } else {
            log('Файл добавлен: '+file.name);
        }

        imgCount++;
        updateInfo();

        // Создаем объект загрузки
        var uploadItem = {
            file: file,
            onProgress: function(percents) {
                updateProgress(pBar, percents);
            },
            onComplete: function(successfully, data, errorCode) {
                if(successfully) {
					var succesc = $('<div/>').addClass('upl_sucs').appendTo(sucsDiv);
					cancelButton.remove();
                    log('Файл `'+this.file.name+'` загружен, полученные данные:<br/>*****<br/>'+data+'<br/>*****');
                } else {
                    if(!this.cancelled) {
						var succesc = $('<div/>').addClass('upl_notsucs').text('!').appendTo(sucsDiv);
                        log('<span style="color: red;">Файл `'+this.file.name+'`: ошибка при загрузке. Код: '+errorCode+'</span>');
                    }
                }
            }
        };

        // ... и помещаем его в очередь
        var queueId = fileInput.damnUploader('addItem', uploadItem);

        // обработчик нажатия ссылки "отмена"
        cancelButton.click(function() {
            fileInput.damnUploader('cancel', queueId);
            li.remove();
            imgCount--;
            imgSize -= file.fake ? 0 : file.size;
            updateInfo();
            log(file.name+' удален из очереди');
            return false;
        });

        return uploadItem;
    }




    ////////////////////////////////////////////////////////////////////////////
    // Обработчики событий


    // Обработка событий drag and drop при перетаскивании файлов на элемент dropBox
    dropBox.bind({
        dragenter: function() {
            $(this).addClass('highlighted');
            return false;
        },
        dragover: function() {
            return false;
        },
        dragleave: function() {
            $(this).removeClass('highlighted');
            return false;
        }
    });


    // Обаботка события нажатия на кнопку "Загрузить все".
    // стартуем все загрузки
    $("#upload-all").click(function() {
        fileInput.damnUploader('startUpload');
    });


    // Обработка события нажатия на кнопку "Отменить все"
    $("#cancel-all").click(function() {
        fileInput.damnUploader('cancelAll');
        imgCount = 0;
        imgSize = 0;
        updateInfo();
        log('*** Все загрузки отменены ***');
        imgList.empty();
    });







    ////////////////////////////////////////////////////////////////////////////
    // Проверка поддержки File API, FormData и FileReader

    if(!$.support.fileSelecting) {
        log('Ваш браузер не поддерживает выбор файлов (загрузка будет осуществлена обычной отправкой формы)');
        $("#dropBox-label").text('если бы ты использовал хороший браузер, файлы можно было бы перетаскивать прямо в область ниже!');
    } else {
        if(!$.support.fileReading) {
            log('* Ваш браузер не умеет читать содержимое файлов (миниатюрки не будут показаны)');
        }
        if(!$.support.uploadControl) {
            log('* Ваш браузер не умеет следить за процессом загрузки (progressbar не работает)');
        }
        if(!$.support.fileSending) {
            log('* Ваш браузер не поддерживает объект FormData (отправка с ручной формировкой запроса)');
        }
        log('Выбор файлов поддерживается');
    }
    log('*** Проверка поддержки ***');

	function getRandImg(){
		$('.img_main_cont_inner img').remove();
		$('.img_main_cont_inner div').remove();
		var images_in_off = $('#images_off_on_val').val();
		var gif_in_off = $('#gif_off_on_val').val()
		$.post("servGetImg.php",{ IMAGES: images_in_off, GIF: gif_in_off }, function(data) {
		
			log(data);
			var arrSize = data.split(':');
			var imgSrc = arrSize[0];
			var imgWidth = arrSize[1];
			var imgHeight = arrSize[2];
			lastId = arrSize[3];
			var imgContHeight = $('.img_main_cont').height();
			var imgContWidth = $('.img_main_cont').width();
			while(imgWidth>imgContWidth||imgHeight>imgContHeight)
			{
				if(imgWidth>imgContWidth)
				{
					var coef = imgContWidth/imgWidth;
					imgWidth = imgContWidth;
					imgHeight = imgHeight*coef;
				}
				if(imgHeight>imgContHeight)
				{
					var coef = imgContHeight/imgHeight;
					imgHeight = imgContHeight;
					imgWidth = imgWidth*coef;
				}			
			}
			$('.img_main_cont_inner ').css({height:parseInt(imgHeight)+10+'px',width:parseInt(imgWidth)+10+'px'});
			var wrap = $('<img/>').attr('src','./items/'+imgSrc).css({height:imgHeight,width:imgWidth}).css('display','none').appendTo('.img_main_cont_inner');
			setTimeout(function(){
				$('.img_main_cont_inner img').css('display','block');
				imgInfo(lastId);
				button_enable = 1;
			},1000);

		});
	}

	function getPrevImg(){
		$('.img_main_cont_inner img').remove();
		$('.img_main_cont_inner div').remove();
		var images_in_off = $('#images_off_on_val').val();
		var gif_in_off = $('#gif_off_on_val').val()
		$.post("getPrevImg.php",{ IMAGES: images_in_off, GIF: gif_in_off, CUR_ID: lastId }, function(data) {
		
			log(data);
			var arrSize = data.split(':');
			var imgSrc = arrSize[0];
			var imgWidth = arrSize[1];
			var imgHeight = arrSize[2];
			lastId = arrSize[3];
			var imgContHeight = $('.img_main_cont').height();
			var imgContWidth = $('.img_main_cont').width();
			while(imgWidth>imgContWidth||imgHeight>imgContHeight)
			{
				if(imgWidth>imgContWidth)
				{
					var coef = imgContWidth/imgWidth;
					imgWidth = imgContWidth;
					imgHeight = imgHeight*coef;
				}
				if(imgHeight>imgContHeight)
				{
					var coef = imgContHeight/imgHeight;
					imgHeight = imgContHeight;
					imgWidth = imgWidth*coef;
				}			
			}
			$('.img_main_cont_inner ').css({height:parseInt(imgHeight)+10+'px',width:parseInt(imgWidth)+10+'px'});
			var wrap = $('<img/>').attr('src','./items/'+imgSrc).css({height:imgHeight,width:imgWidth}).css('display','none').appendTo('.img_main_cont_inner');
			setTimeout(function(){
				$('.img_main_cont_inner img').css('display','block');
				imgInfo(lastId);
				button_enable = 1;
			},1000);

		});
	}

	function getNextImg(){
		$('.img_main_cont_inner img').remove();
		$('.img_main_cont_inner div').remove();
		var images_in_off = $('#images_off_on_val').val();
		var gif_in_off = $('#gif_off_on_val').val()
		$.post("getNextImg.php",{ IMAGES: images_in_off, GIF: gif_in_off, CUR_ID: lastId }, function(data) {
		
			log(data);
			var arrSize = data.split(':');
			var imgSrc = arrSize[0];
			var imgWidth = arrSize[1];
			var imgHeight = arrSize[2];
			lastId = arrSize[3];
			var imgContHeight = $('.img_main_cont').height();
			var imgContWidth = $('.img_main_cont').width();
			while(imgWidth>imgContWidth||imgHeight>imgContHeight)
			{
				if(imgWidth>imgContWidth)
				{
					var coef = imgContWidth/imgWidth;
					imgWidth = imgContWidth;
					imgHeight = imgHeight*coef;
				}
				if(imgHeight>imgContHeight)
				{
					var coef = imgContHeight/imgHeight;
					imgHeight = imgContHeight;
					imgWidth = imgWidth*coef;
				}			
			}
			$('.img_main_cont_inner ').css({height:parseInt(imgHeight)+10+'px',width:parseInt(imgWidth)+10+'px'});
			var wrap = $('<img/>').attr('src','./items/'+imgSrc).css({height:imgHeight,width:imgWidth}).css('display','none').appendTo('.img_main_cont_inner');
			setTimeout(function(){
				$('.img_main_cont_inner img').css('display','block');
				imgInfo(lastId);
				button_enable = 1;
			},1000);

		});
	}	
	//lastId= 1;
	getRandImg();
});
function size_of_divs(){
	var body_h = $(window).height() - 96;
	var body_img_h = $(window).height() - 298;
	$('.main_cont_body').height(body_h);
	$('.img_main_cont').height(body_img_h);

}
function imgInfo(lastId){
	var infCont = $('<div/>').addClass('img_info').appendTo('.img_main_cont_inner');
	$('<a/>').attr('target','_blank').attr('href','/items.php?id='+lastId).text('comments').appendTo(infCont);
	$('<a/>').attr('target','_blank').attr('href',$('.img_main_cont_inner img').attr('src')).text('open original').appendTo(infCont);
}

