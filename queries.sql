-- 	List all of SQL queries based on Miletone6 Project Demo Protocol 


-- display the schema
describe Screentest;
describe Interview;
describe Offer;
describe Apply;
describe Job_Fulltime;
describe Job_Parttime;
describe Job;
describe Recruiter;
describe Applicant;
describe HiringManager;
describe HumanResources;


-- Selection 1point

-- select all columns and rows

SELECT * FROM Screentest;

SELECT * FROM Interview;

SELECT * FROM Offer;

SELECT * FROM Apply;

SELECT * FROM Job_Fulltime;

SELECT * FROM Job_Parttime;

SELECT * FROM Job;

SELECT * FROM Recruiter;

SELECT * FROM Applicant;

SELECT * FROM HiringManager;

SELECT * FROM HumanResources;

-- Insert
INSERT INTO Applicant 
values (307, 'A7', '1234567897', 'Ad7');

SELECT *
FROM Applicant;

-- Delect
DELETE FROM Applicant 
WHERE AID = 304;

SELECT *
FROM Applicant;

-- Update
UPDATE Applicant
SET NAME = 'A6'
WHERE AID=306;

SELECT *
FROM Applicant;

-- select: specify the selection conditions to be returned

SELECT NAME
FROM HumanResources
WHERE HRID>3;

SELECT NAME
FROM HiringManager
WHERE HMID=103;

SELECT PHONE
FROM Applicant
WHERE ADDRESS='Ad1'AND AID=301;



-- Projection 1point

SELECT AID AS ApplicantID,
       Name AS ApplicantName       
FROM Applicant;


SELECT Name AS RecruiterName
FROM Recruiter;


SELECT IID AS InterviewID,
       STATUS
FROM Interview;



-- Join Query 1point

SELECT STATUS, SID
FROM Screentest , Apply 
WHERE Screentest.APID = Apply.APID;


SELECT SALARY
FROM Offer, Apply
WHERE Offer.APID = Apply.APID;



-- Aggregation Query 1point

SELECT AVG(O.SALARY)
FROM OFFER O
WHERE O.STATUS = 'accepted';

SELECT MAX(O.SALARY)
FROM OFFER O
WHERE O.STATUS = 'proposed';



--Nested aggregation with group-by

SELECT O.OFFERID, AVG(O.SALARY)
FROM OFFER O
WHERE O.STATUS = 'accepted'
GROUP BY O.OFFERID;


--Division query 
--find all the applicants ID who complete screentest and  accepted offer

SELECT A.AID
From Screentest S, Offer O, Apply A
where S.STATUS = 'completed' AND
      O.STATUS = 'accepted' AND
      S.APID = O.APID AND
      O.APID = A.APID;




