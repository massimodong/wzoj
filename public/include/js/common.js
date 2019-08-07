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
