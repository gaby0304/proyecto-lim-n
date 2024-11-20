import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ComidasService } from '../../services/comidas.service';
import { Router, RouterModule, RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-actualizarcomidas',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './actualizarcomidas.component.html',
  styleUrls: ['./actualizarcomidas.component.scss']
})
export class ActualizarcomidasComponent implements OnInit {

  publicacion = {
    nombre_comida: '',
    descripcion_comida: '',
    imagen: '',
    elaboracion: ''
  };


  
  mostrarImagen: boolean = false; 

  constructor(private comidasService: ComidasService, private router: Router) {}

  ngOnInit(): void {
    const idComida = localStorage.getItem("idComida");
    console.log('ID de comida desde localStorage:', idComida);
  
    if (idComida) {
      this.getComidaById(parseInt(idComida));  // Si está, lo pasamos al servicio
    } else {
      console.log('No se encontró el ID de comida en localStorage');
    }
  }

  getComidaById(id: number): void {
    this.comidasService.getComidaId(id).subscribe(
      (response) => {
        if (response.data && response.data.length > 0) {
          const comida = response.data[0]; // Accede al primer objeto del arreglo
          this.publicacion = {
            nombre_comida: comida.nombre_comida,
            descripcion_comida: comida.descripcion_comida,
            imagen: comida.imagen,
            elaboracion: comida.elaboracion
          };
          this.router.navigate(['/actualizarComida']); // TENES QUE TENER EL IMPORT EN LA PARTE DE ARRIBA Y COMPONENTS PARA UTILIZAR ESTO.
        } else {
          console.log('No se recibieron datos válidos');
        }
      },
      (error) => {
        console.error('Error al obtener la comida:', error);
      }
    );
  }

  // cambiar por el router cuando el proyecto ya este hecho.
  goBack(): void {
    localStorage.clear();
    window.history.back();
  }


  guardarCambios(formulario: any): void {
    if (formulario.valid) {
      console.log('Formulario válido. Guardando cambios...', this.publicacion);
      const idComida = localStorage.getItem("idComida");  // Obtener el ID de comida del localStorage
      
      if (idComida) {
        this.comidasService.updateComida(parseInt(idComida), this.publicacion).subscribe(
          (response) => {
            alert('Cambios realizados con éxito');
          },
          (error) => {
            alert('Ha ocurrido un error. Por favor, vuelva a intentarlo');
          }
        );
      } else {
        alert('Un error inésperado a ocurrido.');
      }
    } else {
      console.log('Formulario inválido');
    }
  }



// Al chile este lo hice chatgpt. Ni idea de como se hacia esta vara.
MuestraImagen(): void {
  const urlImagen = this.publicacion.imagen;

  if (urlImagen) {
    // Calcular el centro de la pantalla
    const width = 400;  // Ancho de la ventana emergente
    const height = 400; // Alto de la ventana emergente

    const left = (window.innerWidth / 2) - (width / 2);
    const top = (window.innerHeight / 2) - (height / 2);

    // Abre la ventana emergente centrada
    const ventana = window.open('', '_blank', `width=${width},height=${height},left=${left},top=${top}`);
    
    if (ventana) {
      // Escribir contenido HTML dentro de la ventana emergente
      ventana.document.write(`
        <html>
          <head>
            <title>Previsualizacion de imagen de publicación</title>
            <style>
              body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                margin: 0;
                background-color: #f5f5f5;
              }
              img {
                max-width: 100%;
                max-height: 100%;
                display: block;
              }
            </style>
          </head>
          <body>
            <img src="${urlImagen}" alt="Imagen de referencia" />
          </body>
        </html>
      `);
    } else {
      alert('No se pudo abrir la ventana emergente. Asegúrate de que las ventanas emergentes estén permitidas.');
    }
  } else {
    alert('No hay URL de imagen disponible');
  }
}






}

