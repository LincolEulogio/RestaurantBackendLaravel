<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $customers = [
            [
                'customer_dni' => '74231589',
                'customer_name' => 'María',
                'customer_lastname' => 'Gonzales',
                'customer_email' => 'maria.gonzales@demo.com',
                'customer_phone' => '987654321',
                'delivery_address' => 'Av. Javier Prado 1200, San Isidro',
            ],
            [
                'customer_dni' => '71345622',
                'customer_name' => 'Carlos',
                'customer_lastname' => 'Rojas',
                'customer_email' => 'carlos.rojas@demo.com',
                'customer_phone' => '956321478',
                'delivery_address' => 'Calle Los Olivos 245, Surco',
            ],
            [
                'customer_dni' => '70213456',
                'customer_name' => 'Andrea',
                'customer_lastname' => 'Paredes',
                'customer_email' => 'andrea.paredes@demo.com',
                'customer_phone' => '944556677',
                'delivery_address' => 'Jr. Tacna 102, Cercado de Lima',
            ],
            [
                'customer_dni' => '76543210',
                'customer_name' => 'Diego',
                'customer_lastname' => 'Salazar',
                'customer_email' => 'diego.salazar@demo.com',
                'customer_phone' => '933112244',
                'delivery_address' => 'Av. La Marina 988, San Miguel',
            ],
            [
                'customer_dni' => '70125678',
                'customer_name' => 'Lucía',
                'customer_lastname' => 'Fernández',
                'customer_email' => 'lucia.fernandez@demo.com',
                'customer_phone' => '922334455',
                'delivery_address' => 'Pasaje Las Flores 88, Miraflores',
            ],
            [
                'customer_dni' => '73659841',
                'customer_name' => 'José',
                'customer_lastname' => 'Huamán',
                'customer_email' => 'jose.huaman@demo.com',
                'customer_phone' => '911223344',
                'delivery_address' => 'Av. Colonial 560, Callao',
            ],
            [
                'customer_dni' => '74411223',
                'customer_name' => 'Valeria',
                'customer_lastname' => 'Nuñez',
                'customer_email' => 'valeria.nunez@demo.com',
                'customer_phone' => '900112233',
                'delivery_address' => 'Calle París 401, La Molina',
            ],
            [
                'customer_dni' => '70999887',
                'customer_name' => 'Miguel',
                'customer_lastname' => 'Quispe',
                'customer_email' => 'miguel.quispe@demo.com',
                'customer_phone' => '955887766',
                'delivery_address' => 'Av. Canadá 3000, San Borja',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['customer_dni' => $customer['customer_dni']],
                $customer
            );
        }

        $this->command->info('Clientes de demo creados/actualizados correctamente.');
    }
}
