$(document).ready(function(){
	/*
	if($(window).width() < 780){
		$("*").addClass("m");
	}
	*/
	$("#menu-mobile").on("click", function(){
		//if($(this).hasClass("m"))
		{
			var status = $("#menu-mobile ul").attr("class");
			$("#menu-mobile ul").slideToggle("fast");
			if(status == "closed"){
				$("#menu-mobile ul").attr("class", "opened");
				$("#menu-mobile span.select").attr("class", "select opened");
			}else{
				$("#menu-mobile ul").attr("class", "closed");
				$("#menu-mobile span.select").attr("class", "select closed");
			}
		}
	});
		
	$("#content h2").on("click",function(){
		//if($(this).hasClass("m"))
		{
			$(".category-list").slideToggle("fast");
		}
	});
			
	$("input[name=email]").each(function() {
		if(typeof this.class === 'undefined'){
			this.class = '';
		}
		if(typeof this.id === 'undefined'){
			this.id = '';
		}
		if(typeof this.value === 'undefined'){
			this.value = '';
		}
		$(this).replaceWith('<input name="'+this.name+'" type="email" value="'+this.value+'" id="'+this.id+'" class="'+this.class+'">');
	});
				
	$("input[name=telephone]").each(function() {
		if(typeof this.class === 'undefined'){
			this.class = '';
		}
		if(typeof this.id === 'undefined'){
			this.id = '';
		}
		if(typeof this.value === 'undefined'){
			this.value = '';
		}
		$(this).replaceWith('<input name="'+this.name+'" type="tel" value="'+this.value+'" id="'+this.id+'" class="'+this.class+'">');
	});
					
	$("input[name=fax]").each(function() {
		if(typeof this.class === 'undefined'){
			this.class = '';
		}
		if(typeof this.id === 'undefined'){
			this.id = '';
		}
		if(typeof this.value === 'undefined'){
			this.value = '';
		}
		$(this).replaceWith('<input name="'+this.name+'" type="tel" value="'+this.value+'" id="'+this.id+'" class="'+this.class+'">');
	});
		
	//if($("input[name=quantity]").hasClass("m"))
	{
		$("input[name=quantity]").attr("pattern", "[0-9]*");
	}
				
	$(".column h3").on("click", function(e){
		//if($(this).hasClass("m"))
		{
			$(".column ul").hide();
			var ul = $(this).next();
			$(ul).show();
		}
	});
});
/*
$(window).on("resize", function(e){
	if($(this).width() > 780){
		$("*").removeClass("m");
		$(".column ul").show();
	}else{
		$("*").addClass("m");
	}
});
*/
