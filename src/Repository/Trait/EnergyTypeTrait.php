<?php

namespace App\Repository\Trait;

trait EnergyTypeTrait
{
    public function insertDataWithLoadDataInfileSQLFunctionPerEnergy(
        string $fileAbsolutePath,
        string $tableName,
        string $colNumber
    ): void {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();

        // Truncate the table
        $truncateQuery = sprintf('TRUNCATE TABLE %s', $tableName);
        $conn->executeQuery($truncateQuery);

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
                measure_value = %s
            ", $fileAbsolutePath, $tableName, $colNumber);

        $conn->executeQuery($insertQuery);
    }
}
