<?php

function drawTreemap($image, $x, $y, $width, $height, $data)
{
    if (empty($data)) {
        return;
    }

    // Calcular o valor total
    $totalValue = array_sum($data);

    // Calcular a área total disponível
    $totalArea = $width * $height;

    // Calcular a área proporcional para cada item
    foreach ($data as $label => $value) {
        $area = ($value / $totalValue) * $totalArea;

        // Calcular lado do quadrado
        $side = intval(sqrt($area));

        // Verificar se o quadrado cabe na área disponível
        if ($side > $width || $side > $height) {
            $side = min($width, $height);
        }

        // Gerar cor aleatória
        $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));

        // Desenhar quadrado
        imagefilledrectangle($image, intval($x), intval($y), intval($x + $side - 1), intval($y + $side - 1), $color);

        // Calcular a porcentagem e o texto a ser exibido
        $percentage = round(($value / $totalValue) * 100, 2);
        $text = "$label\n$percentage%";

        // Centralizar o texto
        $fontSize = 3; // Tamanho da fonte
        $labelText = "$label";
        $percentageText = "$percentage%";

        // Calcular largura e altura do texto
        $labelWidth = imagefontwidth($fontSize) * strlen($labelText);
        $percentageWidth = imagefontwidth($fontSize) * strlen($percentageText);
        $labelHeight = imagefontheight($fontSize);
        $percentageHeight = imagefontheight($fontSize);

        // Calcular posições para centralizar o texto
        $textX = intval($x + ($side - $labelWidth) / 2);
        $textY = intval($y + ($side - $labelHeight) / 2 - $labelHeight / 2);
        $percentageX = intval($x + ($side - $percentageWidth) / 2);
        $percentageY = intval($y + ($side + $labelHeight) / 2);

        // Desenhar o texto
        imagestring($image, $fontSize, $textX, $textY, $labelText, imagecolorallocate($image, 0, 0, 0));
        imagestring($image, $fontSize, $percentageX, $percentageY, $percentageText, imagecolorallocate($image, 0, 0, 0));

        // Atualizar posição para o próximo quadrado
        if ($width > $height) {
            $x += $side;
            $width -= $side;
        } else {
            $y += $side;
            $height -= $side;
        }

        // Verificar se há espaço suficiente para mais quadrados
        if ($width <= 0 || $height <= 0) {
            break;
        }
    }
}

// dimensões da imagem
$imageWidth = 800;
$imageHeight = 600;


$image = imagecreatetruecolor($imageWidth, $imageHeight);

// cor de fundo aleatoria
$backgroundColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $backgroundColor);

// dados
$data = [
    "A" => 40,
    "B" => 25,
    "C" => 15,
    "D" => 10,
    "E" => 5,
    "F" => 5
];

// cria o treemap
drawTreemap($image, 0, 0, $imageWidth, $imageHeight, $data);

// Enviar o cabeçalho da imagem
header("Content-Type: image/png");

// renderiza a imagem
imagepng($image);

// limpa memoria
imagedestroy($image);
