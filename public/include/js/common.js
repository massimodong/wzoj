function escapeHtml(str){
	var div = document.createElement('div');
	div.appendChild(document.createTextNode(str));
	return div.innerHTML;
}

function addAlertSuccess(message){
  $('#alerts').append("<div class='alert alert-success alert-dismissable fade show' role='alert'>"
      + escapeHtml(message)
      + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"
      + "</div>");
}

function addAlertWarning(message){
  $("#alerts").append("<div class='alert alert-warning alert-dismissable fade show' role='alert'>"
      + escapeHtml(message)
      + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"
      + "</div>");
}

function codeDetectLanguage( code ){
  const langs = {
    1: ['include', '{', '}', 'stdio', 'iostream', 'using', ' namespace ', 'int ', 'main(', //cpp
       'cin>>', 'cout<<', 'scanf(', 'printf(', 'return'],
    2: ['var', 'begin', 'end', 'read(', 'write(', 'then', ':='], //pascal
    4: ['from','import ', 'def ', ' in ', 'elif ', 'input(', 'print('] //python
  };
  var cur_lang = -1, cur_pb = 0.1;
  for(var key in langs){
    if(langs.hasOwnProperty(key)){
      var pb = 0, tot = 0;
      for(var i in langs[key]){
        if(code.indexOf(langs[key][i]) != -1){
          ++pb;
        }
        ++tot;
      }
      pb /= tot;

      if(pb > cur_pb){
        cur_lang = key;
        cur_pb = pb;
      }
    }
  }
  return cur_lang;
}

function socket_init(){
  socket.on('wzoj:App\\Events\\Broadcast', function(msg){
    alert(msg.title+':\n'+msg.content);
  });
}

function changeCaptcha(){
	document.getElementById('captchaImage').src="/_captcha/default?"+Date.now();
}

//inits
$(function () {
  $('.user-badge').popover({
    trigger: 'hover',
    content: function(){
      var img = document.createElement("img");
      img.style = "display: inline-block; vertical-align: top;";
      img.src = $(this).data('avatarurl');

      var h = document.createElement("h5");
      h.style = "margin-bottom: 0";
      h.append($(this).data('fullname'))

      var small = document.createElement("small");
      small.append($(this).data('uname'));

      var p = document.createElement("p");
      p.append($(this).data('description'));

      var cbody = document.createElement("div");
      cbody.style = "display: inline-block; max-width: 122px; max-height: 128px; white-space: normal; overflow: hidden; padding-left: .5rem";
      cbody.appendChild(h);
      cbody.appendChild(small);
      cbody.append(p);

      var ndiv = document.createElement("div");
      ndiv.style = "white-space: nowrap;";
      ndiv.appendChild(img);
      ndiv.appendChild(cbody);
      return ndiv;
    },
    template: '<div class="popover"><div class="arrow"></div><div class="popover-body"></div></div>',
    html: true,
  });
})
