<?php

class m131209_050347_create_region_tables extends CDbMigration
{
	public function safeUp()
	{
            $this->createTable('tbl_region', array(
                'id' => 'pk',
                'region_id' => 'VARCHAR(6) NOT NULL',
                'name' => 'string NOT NULL',
                ), 'ENGINE=InnoDB'
            );
            $this->createTable('tbl_city', array(
                'id' => 'pk',
                'city_id' => 'VARCHAR(6) NOT NULL',
                'name' => 'string NOT NULL',
                'region_id' => 'VARCHAR(6) NOT NULL',
                ), 'ENGINE=InnoDB'
            );
            $this->createTable('tbl_district', array(
                'id' => 'pk',
                'district_id' => 'VARCHAR(6) NOT NULL',
                'name' => 'string NOT NULL',
                'city_id' => 'VARCHAR(6) NOT NULL',
                ), 'ENGINE=InnoDB'
            );
            
            
            $this->createTable('tbl_branch', array(
                'id' => 'pk',
                'name' => 'string NOT NULL',
                'description' => 'text',
                'region_id' => 'VARCHAR(6) DEFAULT NULL',
                'city_id' => 'VARCHAR(6) DEFAULT NULL',
                'district_id' => 'VARCHAR(6) DEFAULT NULL',
                'lng' => 'double(10,6) DEFAULT NULL',
                'lat' => 'double(10,6) DEFAULT NULL',
                ), 'ENGINE=InnoDB'
            );
            
	}

	public function safeDown()
	{
		
            $this->truncateTable('tbl_branch');
            $this->dropTable('tbl_branch');
            $this->truncateTable('tbl_district');
            $this->dropTable('tbl_district');
            $this->truncateTable('tbl_city');
            $this->dropTable('tbl_city');
            $this->truncateTable('tbl_region');
            $this->dropTable('tbl_region');
	}
}