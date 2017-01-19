#!/bin/sh

template=`cat <<EOF 
<div id="speech-@ID@" class=speech-container>\n
\t<span class="speech-time">@TIME@</span>\n
\t<div class="speech-info">\n
\t<h3 class="speech-title">@TITLE@</h3>\n
\t<span class="speech-description">@DESCRIPTION@</span>\n
\t<h3 class="speaker-name">@SPEAKER@</h3>\n
\t<span class="speaker-bio">@RESUME@</span>\n
</div>
EOF`

id=1
grep -v "^#" < Tchelinux2015-Final.csv | while read line
do

    titulo=`echo $line | cut -d@ -f1 | tr -d '"'`
    autor=`echo $line | cut -d@ -f2 | tr -d '"'`
    resumo=`echo $line | cut -d@ -f3 | tr -d '"'`
    curriculo=`echo $line | cut -d@ -f4 | tr -d '"'`
    
    #echo "ID = $id - $titulo" 1>&2
    
    echo $template | sed "
    s#@ID@#$id#
    s#@TITLE@#$titulo#
    s#@SPEAKER@#$autor#
    s#@DESCRIPTION@#$resumo#
    s#@RESUME@#$curriculo#
    "
    id=$[$id + 1]
done
