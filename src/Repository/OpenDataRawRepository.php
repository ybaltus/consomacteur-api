<?php

namespace App\Repository;

use App\Entity\OpenDataRaw;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OpenDataRaw>
 *
 * @method OpenDataRaw|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpenDataRaw|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpenDataRaw[]    findAll()
 * @method OpenDataRaw[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpenDataRawRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpenDataRaw::class);
    }

    public function insertDataWithLoadDataInfileSQLFunction(string $fileAbsolutePath): void
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $tableName = $em->getClassMetadata(OpenDataRaw::class)->getTableName();

        // Truncate the table
        $truncateQuery = sprintf('TRUNCATE TABLE %s', $tableName);
        $conn->executeQuery($truncateQuery);

        // Insert file datas
        $insertQuery = sprintf("
            LOAD DATA LOCAL INFILE '%s'
            INTO TABLE %s
            CHARACTER SET UTF8
            FIELDS TERMINATED BY ';'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (@col0, @col1, @col2, @col3, @col4, @col5, @col6, @col7, @col8, @col9, @col10, @col11, @col12, @col13, @col14, @col15, @col16, @col17, @col18, @col19, @col20, @col21, @col22, @col23, @col24, @col25, @col26, @col27, @col28, @col29, @col30)
            SET code_insee = @col0,
                region = @col1,
                measure_date= @col5,
                consum_electric= @col6,
                consum_thermic= @col7,
                consum_nuclear= @col8,
                consum_wind= @col9,
                consum_solar= @col10,
                consum_hydraulic= @col11
            ", $fileAbsolutePath, $tableName);

        $conn->executeQuery($insertQuery);
    }
}
