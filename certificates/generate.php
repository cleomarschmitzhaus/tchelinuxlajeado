<?php

require('fpdf.php');
require('db.php');

$escape = "";
$do_query = "";
$do_fetch = "";
$error = "";
$close = "";

if ($GLOBALS['vendor'] == 'postgres') {
    $escape = 'pg_escape_string';
    $do_query = 'pg_query';
    $do_fetch = 'pg_fetch_assoc';
    $error = 'pg_last_error';
    $close = 'pg_close';
} else if ($GLOBALS['vendor'] == 'mysql') {
    $escape = 'mysql_real_escape_string';
    $do_query = 'mysql_query';
    $do_fetch = 'mysql_fetch_assoc';
    $error = 'mysql_error';
    $close = 'mysql_close';
} else die("Invalid DB vendor.");

function wiki($pdf, $text)
{
    $count = 0;
    $bold = true;
    $a = split('@',$text);
    foreach ($a as $i => $e) {
        $bold = ! $bold;
        if ($bold) $pdf->SetFont('','B');
        else $pdf->SetFont('','');
        $pdf->Write(10,$e);
    }
}

function generate($FULANO,$palestras,$finger) {
    $MARGIN = 25;

    $pdf = new FPDF('L','mm','A4');
    $pdf->SetLeftMargin($MARGIN);
    $pdf->SetRightMargin($MARGIN);
    $pdf->AddPage();

    $pdf->Image('background.png',90,-10,220);

    $pdf->SetFont('Arial','B',48);
    $pdf->Cell(297-2*$MARGIN,25,'CERTIFICADO',0,1,'C');

    $pdf->SetFont('Arial','B',24);
    $msg = 'Seminário de Software Livre';
    $pdf->Cell(297-2*$MARGIN,35,utf8_decode($msg),0,1,'C');
    $pdf->SetFont('Arial','B',32);
    $pdf->Cell(297-2*$MARGIN,0,'TcheLinux Porto Alegre 2014',0,1,'C');

    $pdf->SetFont('Times','',18);

    $msg = "O Grupo de Usuários de Software Livre TcheLinux certifica que @".$FULANO.
        "@ esteve presente no evento realizado no dia 6 de Dezembro de 2014 ".
        "nas dependências da Faculdade SENAC de Porto Alegre e assistiu as ".
        "palestras:";

    $pdf->SetY(85);
    wiki($pdf, utf8_decode($msg));

    $count = 125;
    foreach($palestras as $_ => $p) {
        $pdf->SetY($count);
        $pdf->SetX(40);
        $msg = $p[0]." (".$p[1].":00h)";
        $pdf->Cell(200,1,utf8_decode($msg),0,0,'L');
        $count = $count + 8;
    }

    $pdf->SetFont('Times','',12);

    $pdf->SetY(-30.5);
    $pdf->SetX(-78);
    $pdf->Cell(20,0,utf8_decode("Código de Confirmação:"),0,0,'R');
    $pdf->SetY(-25);
    $pdf->SetX(-114);
    $pdf->SetFont('','B');
    $pdf->Cell(10,0,$finger,0,1,'L');
    $pdf->SetFont('','');
    $pdf->SetY(-20.5);
    $pdf->SetX(-65.5);
    $pdf->Cell(20,0,'http://poa.tchelinux.org/certificado.php',0,0,'R');

    $pdf->Output();
}

function retrieve($code) {
    global $escape, $do_query, $do_fetch, $error;
    
    $conn = connect();
    
    $code = $escape($code);
    $query = "SELECT * FROM certificados WHERE certificate_code = '".$code."'";
    $rs = $do_query($query);
    $row = $do_fetch($rs);
    echo '<html><head><meta charset="UTF-8"></head><body>';
    if ($row) {
        $pid = $row['participante_id'];

        $query_nome = "select nome from participantes where id = $pid";
        $rs = $do_query($query_nome);
        $row = $do_fetch($rs);
        if ($row) {
            $nome = utf8_decode($row['nome']);
            echo "<h1>C&oacute;digo V&aacute;lido</h1>";
            echo "<p>C&oacute;digo: <b>$code</b></p>";
            echo "<p>Participante: <b>$nome</b></p>";
            echo "<p>Palestras</p><p><ul>";

            $query_palestras = "select palestra_id from participacao where participante_id = $pid";
            $rs = $do_query($query_palestras);
            while ($row = $do_fetch($rs)) {
                $query_titulo = "select * from palestras where id = ${row['palestra_id']}";
                $prs = $do_query($query_titulo);
                $prow = $do_fetch($prs);
                $titulo = iconv('mac','utf-8',utf8_decode($prow['titulo']));
                $duracao = $prow['duracao'];
                if ($prow)
                    echo "<li>$titulo ( $duracao:00h )</li>";
            }
            echo "</ul></p>";
        }
    } else {
        echo "<H1>C&oacute;digo Inv&aacute;lido!</H1>";
    }
    echo "</body></html>";

}

function get_name($oname) {
    global $escape, $do_query, $do_fetch, $error;

    $pid_query = "SELECT id, nome FROM participantes WHERE nome = ";
    $name = $escape($oname);
    $names = array($name, iconv('mac','utf-8',$name), utf8_encode($name), utf8_decode($name));
    foreach ($names as $_ => $name) {
        $rs = $do_query($pid_query."'".$name."'");
        $row = $do_fetch($rs);
        if ($row) return $row;
    }

    return false;
}

function get_palestras($participante_id) {
    global $escape, $do_query, $do_fetch, $error;

    $palestras = array();
    $q_palestras = 
        'SELECT * FROM palestras WHERE id IN '.
        '(SELECT palestra_id FROM participacao WHERE participante_id = '.
        $participante_id.')';
   
    $rs = $do_query($q_palestras);
    while ($row = $do_fetch($rs)) {
        array_push($palestras, array(
                    iconv('mac','utf-8',utf8_decode($row['titulo'])),
                    $row['duracao']));
    }
    return $palestras;
}

function generate_certificate_code($name, $event_date, $palestras) {
    global $escape, $do_query, $do_fetch, $error;

    $p = "";
    foreach ($palestras as $_ => $data)
        $p .= $data[0];
    return hash("md5",$name.$p.$event_date);
}

function update_certificate_db($participante_id, $event_date, $fingerprint) {
    global $escape, $do_query, $do_fetch, $error;

    $q_certificado =
        "SELECT * FROM certificados ".
        "WHERE event = '$event_date' AND participante_id = $participante_id";
    $rs = $do_query($q_certificado);
    if ($row = $do_fetch($rs)) {
        $q_update =
            "UPDATE certificados SET certificate_code = '$fingerprint' ".
            "WHERE event = '$event_date' AND participante_id = $participante_id";
    } else {
        $q_update =
            "INSERT INTO certificados ".
            "VALUES ('$fingerprint', '$event_date', $participante_id)";
    }
    $do_query($q_update);
}

function consult_db($name) {
    global $escape, $do_query, $do_fetch, $error;

    $conn = connect();
    $row = get_name($name) or die("Could not retrieve name.\n".$error());
    if ($row) {
        $name = $row['nome'];
        $pid = $row['id'];
        if ($pid) {
            $palestras = get_palestras($pid);
            if (count($palestras) == 0) {
                header("Location: ../certificado.php");
                return false;
            }
            # TODO: date is still hard coded (YYYY-MM-DD).
            $finger = generate_certificate_code($name, '2014-12-06', $palestras);
            # TODO: date is still hard coded (YYYY-MM-DD).
            update_certificate_db($pid, '2014-12-06', $finger);
            # generate PDF
            generate(utf8_decode($name), $palestras, $finger);
        }
    }

    $close($conn);
}

# gera certificado
#$_POST["name"] = "Marcelo Baldissera Cure";
#$_POST["name"] = "Gabriela Correa";
#$_POST['name'] = "Éderson Tiago Szlachta";
# sem palestras
#$_POST["name"] = "ÂNDREI D'OLIVEIRA MESQUITA SCHUCH";

# main()

if ( isset($_POST["code"]) && $_POST["code"] != "") {
    retrieve($_POST["code"]);
} else if ( isset($_POST["name"]) && $_POST["name"] != "") {
    consult_db($_POST["name"]);
} else
    generate("Rafael Guterres Jeffman",array("Python do Zero ao Minority Report"),"2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e730");

?>
