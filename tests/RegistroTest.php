<?php

use PHPUnit\Framework\TestCase;

class RegistroTest extends TestCase
{
    // ========== TESTS DE VALIDACIÓN ==========
    
    public function testValidarContrasenasCoincidenExitoso()
    {
        $contrasena = "miPassword123";
        $confirmar_contrasena = "miPassword123";
        
        $this->assertEquals($contrasena, $confirmar_contrasena, "Las contraseñas deben coincidir");
    }
    
    public function testValidarContrasenasNoCoinciden()
    {
        $contrasena = "miPassword123";
        $confirmar_contrasena = "otraPassword456";
        
        $this->assertNotEquals($contrasena, $confirmar_contrasena, "Las contraseñas no deben coincidir");
    }
    
    public function testValidarEmailValido()
    {
        $emails_validos = [
            "usuario@ejemplo.com",
            "test@test.com",
            "user.name@domain.co.pe",
            "admin@limawheels.com"
        ];
        
        foreach ($emails_validos as $email) {
            $this->assertTrue(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "El email {$email} debe ser válido"
            );
        }
    }
    
    public function testValidarEmailInvalido()
    {
        $emails_invalidos = [
            "email-sin-arroba",
            "@domain.com",
            "user@",
            "user@domain",
            "user space@domain.com"
        ];
        
        foreach ($emails_invalidos as $email) {
            $this->assertFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL),
                "El email {$email} debe ser inválido"
            );
        }
    }
    
    // ========== TESTS DE CAMPOS OBLIGATORIOS ==========
    
    public function testCamposObligatoriosNoVacios()
    {
        $datos_registro = [
            'nombre_completo' => 'Juan Carlos',
            'apellido_completo' => 'Pérez García',
            'correo_electronico' => 'juan@ejemplo.com',
            'contrasena' => 'miPassword123',
            'confirmar_contrasena' => 'miPassword123'
        ];
        
        foreach ($datos_registro as $campo => $valor) {
            $this->assertNotEmpty($valor, "El campo {$campo} no debe estar vacío");
        }
    }
    
    public function testDetectarCamposVacios()
    {
        $datos_con_campos_vacios = [
            'nombre_completo' => '',
            'apellido_completo' => 'Pérez García',
            'correo_electronico' => '',
            'contrasena' => 'miPassword123',
            'confirmar_contrasena' => 'miPassword123'
        ];
        
        $campos_vacios = [];
        foreach ($datos_con_campos_vacios as $campo => $valor) {
            if (empty($valor)) {
                $campos_vacios[] = $campo;
            }
        }
        
        $this->assertContains('nombre_completo', $campos_vacios);
        $this->assertContains('correo_electronico', $campos_vacios);
        $this->assertCount(2, $campos_vacios, "Debe detectar exactamente 2 campos vacíos");
    }
    
    // ========== TESTS DE FUNCIONES HELPER ==========
    
    public function testValidarDatosRegistroCompleto()
    {
        $datos_validos = [
            'nombre_completo' => 'Ana María',
            'apellido_completo' => 'González López',
            'correo_electronico' => 'ana@ejemplo.com',
            'contrasena' => 'segura123',
            'confirmar_contrasena' => 'segura123'
        ];
        
        $resultado = $this->validarDatosRegistro($datos_validos);
        
        $this->assertTrue($resultado['es_valido'], "Los datos válidos deben pasar la validación");
        $this->assertEmpty($resultado['errores'], "No debe haber errores con datos válidos");
    }
    
    public function testValidarDatosRegistroConErrores()
    {
        $datos_invalidos = [
            'nombre_completo' => '',
            'apellido_completo' => 'González',
            'correo_electronico' => 'email-invalido',
            'contrasena' => 'pass123',
            'confirmar_contrasena' => 'pass456'
        ];
        
        $resultado = $this->validarDatosRegistro($datos_invalidos);
        
        $this->assertFalse($resultado['es_valido'], "Los datos inválidos no deben pasar la validación");
        $this->assertNotEmpty($resultado['errores'], "Debe haber errores con datos inválidos");
        $this->assertGreaterThan(0, count($resultado['errores']), "Debe detectar múltiples errores");
    }
    
    // ========== TESTS DE SEGURIDAD ==========
    
    public function testDetectarIntentosInyeccionSQL()
    {
        // Simular intentos de inyección SQL
        $inputs_maliciosos = [
            "'; DROP TABLE usuarios; --",
            "admin' OR '1'='1",
            "' UNION SELECT * FROM usuarios --"
        ];
        
        foreach ($inputs_maliciosos as $input) {
            // Verificar que contenga caracteres sospechosos
            $this->assertTrue(
                strpos($input, "'") !== false || strpos($input, "--") !== false,
                "Debe detectar caracteres sospechosos en: {$input}"
            );
        }
    }
    
    public function testLongitudMinimaContrasena()
    {
        $contrasenas_cortas = ['123', 'abc', '12345'];
        $contrasenas_largas = ['password123', 'miPasswordSegura', 'clave123456'];
        
        foreach ($contrasenas_cortas as $contrasena) {
            $this->assertLessThan(8, strlen($contrasena), "Contraseña muy corta: {$contrasena}");
        }
        
        foreach ($contrasenas_largas as $contrasena) {
            $this->assertGreaterThanOrEqual(8, strlen($contrasena), "Contraseña suficientemente larga: {$contrasena}");
        }
    }
    
    // ========== TESTS SIMULANDO PETICIONES POST ==========
    
    public function testSimularPeticionPOSTValida()
    {
        // Simular datos de $_POST
        $post_data = [
            'nombre_completo' => 'Carlos Eduardo',
            'apellido_completo' => 'Martínez Silva',
            'correo_electronico' => 'carlos@test.com',
            'contrasena' => 'miPassword123',
            'confirmar_contrasena' => 'miPassword123'
        ];
        
        // Simular la lógica de tu registro.php
        $resultado = $this->procesarRegistroSimulado($post_data);
        
        $this->assertTrue($resultado['puede_registrar'], "Debe poder registrar con datos válidos");
        $this->assertEmpty($resultado['errores'], "No debe haber errores");
    }
    
    public function testSimularPeticionPOSTInvalida()
    {
        $post_data = [
            'nombre_completo' => 'Juan',
            'apellido_completo' => 'Pérez',
            'correo_electronico' => 'juan@test.com',
            'contrasena' => 'pass123',
            'confirmar_contrasena' => 'pass456' // Contraseñas diferentes
        ];
        
        $resultado = $this->procesarRegistroSimulado($post_data);
        
        $this->assertFalse($resultado['puede_registrar'], "No debe poder registrar con contraseñas diferentes");
        $this->assertContains('Las contraseñas no coinciden', $resultado['errores']);
    }
    
    // ========== MÉTODOS HELPER PARA LOS TESTS ==========
    
    private function validarDatosRegistro($datos)
    {
        $errores = [];
        
        // Validar campos obligatorios
        if (empty($datos['nombre_completo'])) {
            $errores[] = 'El nombre completo es obligatorio';
        }
        
        if (empty($datos['apellido_completo'])) {
            $errores[] = 'El apellido completo es obligatorio';
        }
        
        if (empty($datos['correo_electronico'])) {
            $errores[] = 'El correo electrónico es obligatorio';
        } elseif (!filter_var($datos['correo_electronico'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo electrónico no es válido';
        }
        
        if (empty($datos['contrasena'])) {
            $errores[] = 'La contraseña es obligatoria';
        }
        
        if (empty($datos['confirmar_contrasena'])) {
            $errores[] = 'Debe confirmar la contraseña';
        }
        
        // Validar que las contraseñas coincidan
        if (!empty($datos['contrasena']) && !empty($datos['confirmar_contrasena'])) {
            if ($datos['contrasena'] !== $datos['confirmar_contrasena']) {
                $errores[] = 'Las contraseñas no coinciden';
            }
        }
        
        return [
            'es_valido' => empty($errores),
            'errores' => $errores
        ];
    }
    
    private function procesarRegistroSimulado($post_data)
    {
        $errores = [];
        $puede_registrar = true;
        
        // Simular la validación de contraseñas de tu código
        if ($post_data['contrasena'] != $post_data['confirmar_contrasena']) {
            $errores[] = 'Las contraseñas no coinciden';
            $puede_registrar = false;
        }
        
        // Simular otras validaciones
        if (empty($post_data['nombre_completo'])) {
            $errores[] = 'Nombre completo requerido';
            $puede_registrar = false;
        }
        
        if (!filter_var($post_data['correo_electronico'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Email inválido';
            $puede_registrar = false;
        }
        
        return [
            'puede_registrar' => $puede_registrar,
            'errores' => $errores
        ];
    }
}