-- DROP DATABASE hiddlestick;
-- DROP USER 'hiddlestick_r'@'localhost';
-- DROP USER 'hiddlestick_rw'@'localhost';

CREATE DATABASE hiddlestick;

CREATE USER hiddlestick_r@localhost 
  IDENTIFIED BY 'hiddlestick_r';
CREATE USER hiddlestick_rw@localhost 
  IDENTIFIED BY 'hiddlestick_rw';

GRANT SELECT ON hiddlestick.* TO hiddlestick_r@localhost;
GRANT ALL ON hiddlestick.* TO hiddlestick_rw@localhost;

FLUSH PRIVILEGES;