-- -*- mode:sql; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:

/* Data Jukebox Bundle
 *
 * Copyright (C) 2015 Idiap Research Institute <http://www.idiap.ch>
 * Author: Cedric Dufour <http://cedric.dufour.name>
 *
 * This file is part of Data Jukebox Bundle.
 *
 * The Data Jukebox Bundle is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, Version 3.
 *
 * The Data Jukebox Bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 */


/*
 * TABLE: SampleBlogCategory
 ********************************************************************************/

CREATE TABLE SampleBlogCategory (
  pk integer NOT NULL AUTO_INCREMENT,
  Name_vc varchar(100) NOT NULL,
  Description_tx text,
  PRIMARY KEY (pk)
);


/*
 * TABLE: SampleBlogEntry
 ********************************************************************************/

CREATE TABLE SampleBlogEntry (
  pk integer NOT NULL AUTO_INCREMENT,
  Category_fk integer NOT NULL,
  Title_vc varchar(100) NOT NULL,
  Date_d date NOT NULL,
  Content_tx text,
  Tags_vc varchar(1000),
  PRIMARY KEY (pk)
);

ALTER TABLE SampleBlogEntry
  ADD CONSTRAINT SampleBlogEntry_Category_fk
  FOREIGN KEY (Category_fk)
  REFERENCES SampleBlogCategory (pk)
  ON DELETE RESTRICT
;


/*
 * VIEW: SampleBlogEntry
 ********************************************************************************/

CREATE OR REPLACE VIEW SampleBlogEntry_view AS
SELECT
  SampleBlogEntry.*,
  SampleBlogCategory.Name_vc AS Category_vc
FROM
  SampleBlogEntry
INNER JOIN
  SampleBlogCategory
  ON SampleBlogCategory.pk = SampleBlogEntry.Category_fk
;
