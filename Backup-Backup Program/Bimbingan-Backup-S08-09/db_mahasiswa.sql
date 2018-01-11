Create table mahasiswa
( nim char(8) not null,
nama varchar(30),
ttl date,
jk char(1) not null,
alamat varchar(30),
primary key (nim)
);

Create table kuliah
( kode_mk char(8) not null,
nama_mk varchar(30),
sks smallint,
semester smallint,
primary key (kode_mk)
);

Create table nilai
( nim char(8) not null,
kode_mk varchar(30) not null,
uts smallint,
uas smallint,
na float(2),
hm char(1) default 'T',
primary key (nim,kode_mk)
);

Insert into mahasiswa values ("10106001", "Arya Santoso", "1983-12-01", "l", "Dago -
Bandung");
Insert into mahasiswa (nim, nama, ttl, jk, alamat)
values ("10106002", "Astrid Ardia", "1984-04-23", "p", "Nginden - Surabaya");
Insert into mahasiswa values ("10106003", "Budi Arga", "1984-10-24", "l", "Cicaheum - Bandung");
Insert into mahasiswa values ("10106004", "Dini Andari", "1983-01-23", "p", "Menteng - Jakarta");
Insert into mahasiswa values ("10106005", "Dwi Ciska", "1985-12-29", "p", "Merdeka - Malang");
Insert into mahasiswa values ("10106006", "Edi Prastowo", "1984-07-07", "l", "Dago - Bandung");
Insert into mahasiswa values ("10106007", "Eka Sapta", "1984-02-24", "l", "Setiabudi - Bandung");
Insert into mahasiswa values ("10106008", "Fifin Aliana", "1984-10-21", "p", "Mande - Mataram");
Insert into mahasiswa values ("10106009", "Giri Rekso", "1983-11-17", "l", "Perak - Surabaya");
Insert into mahasiswa values ("10106010", "Heri Ahmad Surya", "1985-04-06", "l", "Antapani - Bandung");

Insert into kuliah values ("IF32101", "Algoritma dan Pemrograman II", 2, 2);
Insert into kuliah values ("IF32209", "Kalkulus II", 3, 2);
Insert into kuliah values ("IF33217", "Organisasi Komputer", 3, 3);
Insert into kuliah values ("IF33302", "Pemrograman I", 2, 3);
Insert into kuliah values ("IF34332", "Basis Data", 3, 4);
Insert into kuliah values ("IF34222", "Struktur Data", 3, 4);
Insert into kuliah values ("IF35333", "Sistem Basis Data", 3, 5);
Insert into kuliah values ("IF35317", "Sistem Informasi", 3, 5);
Insert into kuliah values ("IF36319", "Sistem Operasi", 3, 6);
Insert into kuliah values ("IF36315", "Riset Operasional", 3, 6);
Insert into kuliah values ("IF37321", "Kecerdasan Buatan", 3, 7);

Insert into nilai values ("1010601", "IF32101",70,80, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010601", "IF32209",50,89, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010601", "IF33217",78,80, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010601", "IF33302",89,78, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010602", "IF32101",80,90, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010602", "IF32209",55,88, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010602", "IF33217",46,70, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010603", "IF37321",80,70, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010603", "IF33302",69,48, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010604", "IF37321",68,88, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010605", "IF33302",59,75, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010604", "IF35333",80,64, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010604", "IF33302",79,69, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010605", "IF35333",60,60, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010606", "IF32101",80,80, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010606", "IF34222",58,59, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010607", "IF32101",79,79, (0.4*uts) + (0.6*uas),NULL);
Insert into nilai values ("1010607", "IF34222",70,78, (0.4*uts) + (0.6*uas),NULL);

Update nilai
Set hm='A' where na >= 80 and na <= 100;
Update nilai
Set hm='B' where na >= 68 and na < 80;
Update nilai
Set hm='C' where na >= 56 and na < 68;
Update nilai
Set hm='D' where na >= 45 and na < 56;
Update nilai
Set hm='E' where na >= 0 and na < 45;















