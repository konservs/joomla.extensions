function removeClass(elements, classname){	
	var regex = new RegExp("(^|\\s)" + classname + "(\\s|$)");	
	var curr_class, list = [];
	for(var i = 0; i < elements.length; i++){
		list[i] = elements[i];
	}	
	for(var i = 0; i < list.length; i++){
		if(regex.test(list[i].className)){				
			curr_class = list[i].className;		
			list[i].className = curr_class.replace(classname, '').replace(/ /g,'');				
		}
	}	
}

function addClass(element, classname){
	if(element.className != ''){
		element.className += ' ' + classname;
	}else{
		element.className = classname;
	}	
}

function addEventListenersToElements(elements, event, fn){
	for(var i = 0; i < elements.length; i++){
		elements[i].addEventListener(event, fn, false)
	}
}

function changeTab(){
	var tabs_content = document.getElementById('tabs_content').children;
	removeClass(document.getElementsByClassName('active'), 'active');
	addClass(this, 'active');
	addClass(document.getElementById(this.getAttribute('for')), 'active');	
}

window.onload = function(){  
	if (!document.getElementsByClassName){
	    document.getElementsByClassName = function(classname) {
	        var elArray = [];
	        var tmp = document.getElementsByTagName("*");
	        var regex = new RegExp("(^|\\s)" + classname + "(\\s|$)");
	        for (var i = 0; i < tmp.length; i++){
	            if (regex.test(tmp[i].className)){
	                elArray.push(Object(tmp[i]));
	            }
	        }
	        return elArray;
	    };
	} 
	//get tabs
	var tabs = document.getElementById('tabs');
	if(tabs!=undefined){
		var tabsc=tabs.children;
		//add onclick event to tabs;
		addEventListenersToElements(tabsc, 'click', changeTab);	
		}
	}
