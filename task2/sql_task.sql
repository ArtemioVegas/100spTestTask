-- 1. На выборку всех категорий верхнего уровня, начинающихся на “авто”

ALTER TABLE category ADD INDEX (name, parent_category_id);

SELECT * 
FROM category 
WHERE name LIKE 'авто%' 
AND parent_category_id = 0;

-- 2. На выборку всех категорий, имеющих не более трёх подкатегорий следующего уровня (без глубины)

SELECT 
    c2.id, c2.name, COUNT(*) AS 'cnt'
FROM
    category c1
        LEFT JOIN
    category c2 ON c1.parent_category_id = c2.id
GROUP BY c1.parent_category_id
HAVING cnt <= 3;

-- 3. На выборку всех категорий нижнего уровня (т.е. не имеющих детей)
ALTER TABLE category ADD INDEX (parent_category_id);

SELECT
    c1.id, c1.name
FROM
    category c1
        LEFT JOIN
    category c2 ON c2.parent_category_id = c1.id
WHERE
    c2.id IS NULL;