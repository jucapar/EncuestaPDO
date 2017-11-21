CREATE DATABASE DAW202_DBEncuesta;
USE DAW202_DBEncuesta;
CREATE TABLE Encuesta(
	DNI VARCHAR(9) NOT NULL,
	Nombre VARCHAR(50) NOT NULL,
	Apellido1 VARCHAR(50) NOT NULL,
	Apellido2 VARCHAR(50) NOT NULL,
	Telefono VARCHAR(9) NOT NULL,
	Email VARCHAR(50) NOT NULL,
	FechaNacimiento DATE NOT NULL,
        HorasEstudio INT NOT NULL,
	GradoSatisfaccion INT NOT NULL,
	Valoracion VARCHAR(20),
	Opiniones VARCHAR(255),
	IP VARCHAR(15) NOT NULL,
	PRIMARY KEY (DNI)
)ENGINE=InnoDB;
