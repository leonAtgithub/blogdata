CREATE TABLE IF NOT EXISTS `pfs_eintraege` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `body` text NOT NULL,
    `created_date` timestamp DEFAULT CURRENT_TIMESTAMP,
    `modifiy_date` timestamp,
    PRIMARY KEY (`id`)
) ;


create table if not exists `pfs_props` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ent_id` int(11) NOT NULL,-- refernz key zu pfs_eintraege
    `pfs_propdef_id` int(11) NOT NULL, -- refrenz zu property definitonen `pfs_propdef`
    `v_numeric` float,-- Value feld für float
    `v_bool` BOOL, -- value feld für true/false
    `v_link` int(11),-- value feld für link zu anderem Eintrag
    PRIMARY KEY (`id`)

);

create table if not exists `pfs_propdef`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `typ` ENUM('v_numeric', 'v_bool', 'v_link'),-- Einer von v_link, v_numeric , v_bool (Namensgelcih zu den feldern in pfs_props)
    `adverb` varchar(50) NOT NULL UNIQUE ,-- Einfacher string der die Bezeihung ausdrueckt  uses_procedure
    PRIMARY KEY (`id`)
);

/*
INSERT INTO pfs_propdef SET typ='v_numeric' ,adverb='has_price';
INSERT INTO pfs_propdef SET typ='v_bool' ,adverb='has_worked';

INSERT INTO pfs_eintraege SET body='A random entry describing foo stuff';
INSERT INTO pfs_eintraege SET body='Another ';        

INSERT INTO pfs_props SET ent_id=1, pfs_propdef_id = 1 , v_numeric= 15 ;
INSERT INTO pfs_props SET ent_id=1, pfs_propdef_id = 2 , v_bool= 1 ;
INSERT INTO pfs_props SET ent_id=2, pfs_propdef_id = 1 , v_numeric= 15 ;
INSERT INTO pfs_props SET ent_id=2, pfs_propdef_id = 2 , v_bool= 0 ;
*/
-- Bsp. query: select * from pfs_props as p join pfs_propdef as pdef on p.pfs_propdef_id=pdef.id where pdef.adverb="has_price";
/* Bsp. query select (pfs_eintraege.body) from pfs_props as p join pfs_propdef as pdef on p.pfs_propdef_id=pdef.id, pfs_props as pi join pfs_eintraege on pi.ent_id=pfs_eintraege.id where pdef.adverb="has_price";

*/

select e.id, body ,adverb, 
(case 
    when v_numeric IS not null then v_numeric 
    when v_bool IS not null then v_bool
    when v_link IS NOT NULL then v_link
end) as value
from pfs_eintraege as e 
join pfs_props as p on e.id = p.ent_id 
join pfs_propdef as pf on p.pfs_propdef_id = pf.id ;


