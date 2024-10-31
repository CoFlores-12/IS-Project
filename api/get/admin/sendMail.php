<?php
    $user = 'daav690@gmail.com';
    $password = 'bhrg fstt gwav kssy'; // Cambiar por la contraseña real o una contraseña de aplicación


    $aspirantes = [
        [
            'nombre' => 'Daniel Avila',
            'correo' => 'daavilav@unah.hn',
            'estatus' => 'aprobado'
        ],
        [
            'nombre' => 'Cesar Flores',
            'correo' => 'obethflores2014@gmail.com',
            'estatus' => 'reprobado :('
        ]
        
    ];
    include '../../../src/modules/mails.php';

    $mail = new Mails($user, $password);


    foreach ($aspirantes as $applicant) {
        $name = $applicant['nombre'];
        $mailAspirant = $applicant['correo'];
        $status = $applicant['estatus'];
        $affair = "Estatus de Examen de Admisión";
        $message = "Hola $name,<br><br>Tu estatus de examen de admisión es: <strong>$status</strong>.<br><br>Saludos,<br>Equipo de Admisiones.";
    
        $resultado = $mail->sendEmail($user, $mailAspirant, $affair, $message);
        echo $resultado . "<br>"; // Muestra el resultado de cada envío
    }

?>

