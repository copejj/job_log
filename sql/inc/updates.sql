update address_types
set name = ''
where address_type_id = 1;

delete from address_types
where address_type_id = 2;
