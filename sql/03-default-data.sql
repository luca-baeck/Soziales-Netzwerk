INSERT INTO PermissionLevel
    VALUES (255, 'admin'),
           (1, 'user'),
           (0, 'guest');

INSERT INTO UploadType
    VALUES (1, 'media'),
           (2, 'profilepicture'),
           (3, 'sticker');

INSERT INTO User (ID, Handle, Name, Password) VALUES ('fedcba98765432100123456789abcdef', 'hiddlestick', 'hiddlestick', 'db0ca17e791eaff553ca3145f8e513ead3f20ad55c8e57d85c6987239dbde4d3');
Password für Hiddlestick: sda.kjfhsadfasDFDSAÖJFOLkasdoäöf

INSERT INTO Sticker (ID, Name, CreatorID) VALUES ('fedcba98765432111123456789abcdef', '1', 'fedcba98765432100123456789abcdef');
INSERT INTO Sticker (ID, Name, CreatorID) VALUES ('fedcba98765432122123456789abcdef', '2', 'fedcba98765432100123456789abcdef');
INSERT INTO Sticker (ID, Name, CreatorID) VALUES ('fedcba98765432133123456789abcdef', '3', 'fedcba98765432100123456789abcdef');
