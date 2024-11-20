import { Component, OnInit } from '@angular/core';
import { CommonModule} from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ComidasService } from '../../services/comidas.service';
import { Router, RouterModule, RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-comidas',
  standalone: true,
  imports: [CommonModule, FormsModule,RouterOutlet, RouterModule],
  templateUrl: './comidas.component.html',
  styleUrls: ['./comidas.component.scss']
})
export class ComidasComponent implements OnInit {
  titulo: string = '';  // para modificar input del html 

  publicaciones: any[] = [];   
  publicacionesFiltradas: any[] = [];


  textoBoton: string = 'Buscar'; // buscar - para restablecer


  ultimaBusqueda: string = ''; // Guarda el valor de la última búsqueda
 
  paginaActual: number = 1; // Página actual
  publicacionesPorPagina: number = 5; // Cantidad de publicaciones por página
  //private ManejaIntervalo: any;  // Esto aún tiende a dar error OJO

  constructor(private comidasService: ComidasService, private router: Router) {}

  ngOnInit(): void {
    this.getComidas(); // Carga inicial de todas las publicaciones
 
 
    //  this.ManejaIntervalo = setInterval(() => {
  //    this.getComidas();  //Hilo que refresca las comidas que existen de la bd.
   // }, 5000);  // Me estaba dando un error medio extraño.
  }

  getComidas(): void {
    this.comidasService.getComidas().subscribe(
      (resp) => {
        if (resp && resp.data) {
          this.mapearPublicaciones(resp.data);
          this.resetBoton(); // Restablece el botón a "Buscar"
          this.actualizarPaginacion(); // Divide en páginas
        }
      },
      (error) => {
        console.error('Error al obtener las comidas:', error);
      }
    );
  }

  filtrarComidas(): void {
    if (this.titulo.trim() === '') {
      this.cambiaTexto();
      this.getComidas();
    } else if (this.titulo === this.ultimaBusqueda) {
      this.cambiaTexto();
      this.getComidas();
    } else {
      this.comidasService.filtrarComidas(this.titulo).subscribe(
        (resp) => {
          if (resp && resp.data) {
            this.mapearPublicaciones(resp.data);
            this.ultimaBusqueda = this.titulo;
            this.textoBoton = 'Restablecer'; // Cambiar el texto del botón
            this.actualizarPaginacion(); // Divide en páginas
          } else {
            this.publicaciones = [];
            console.warn('No hay datos disponibles para el filtro.');
            this.cambiaTexto();
            this.titulo = '';
          }
        },
        (error) => {
          console.error('Error al filtrar las comidas:', error);
        }
      );
    }
  }

  mapearPublicaciones(data: any[]): void {
    this.publicaciones = data.map((comida: any) => ({
      fecha: comida.fecha_creacion,
      usuario: `@UsuarioID${comida.usuario_id}`,
      nombre: comida.nombre_comida,
      descripcion: comida.descripcion_comida,
      imagenUrl: comida.imagen,
      idComida : comida.id_comida
    }));
  }



  

  get totalPaginas(): number[] {
    return Array(Math.ceil(this.publicaciones.length / this.publicacionesPorPagina));
  }


  actualizarPaginacion(): void {
    const inicio = (this.paginaActual - 1) * this.publicacionesPorPagina;
    const fin = inicio + this.publicacionesPorPagina;
    this.publicacionesFiltradas = this.publicaciones.slice(inicio, fin);
  }

  cambiarPagina(pagina: number): void {
    this.paginaActual = pagina;
    this.actualizarPaginacion();
  }

  resetBoton(): void {
    this.textoBoton = 'Buscar';
    this.ultimaBusqueda = '';
  }

  cambiaTexto(): void {
    this.textoBoton = 'Restablecer';
  }

  onInputChange(): void {
    if (this.titulo !== this.ultimaBusqueda) {
      this.textoBoton = 'Buscar';
    }
  }


  
  onSearch(): void {
    this.filtrarComidas();
  }
 

  // ESTO DEBE SER MODIFICADO POR UN ROUTERLINK CUANDO ESTE TODO EL PROYECTO ARMADO
  goBack(): void {
    window.history.back();
  }
   

  haciaPublicacionEspecifica(event: MouseEvent,idComida: number) : void{
    localStorage.setItem("idComida", idComida.toString()); 
    //alert('Editar publicación con ID: ' + localStorage.getItem("idComida"));
    
    this.router.navigate(['/comidaEspecifica']); //PARA QUE ESTO FUNCIONE SE DEBE PONER EN EL CONSTRUCTOR JUNTO AL SERVICIO.
   
  }



  // los dos primeros manejadores de eventos evitan errores medios raros de propagación. En definitiva NO TOCARLOS.
  EditarPublicacion(event: MouseEvent,idComida: number): void {
    event.stopPropagation();  // Esto previene que el <a> se active
    event.preventDefault(); // este al parecer funciona super bien en conjunto con el anterior. Para evitar la caida de la pagina

   
    const IDComida = (idComida); // Almaceno id de publicacion especifica por parametro
    localStorage.setItem("idComida", idComida.toString()); 
    //alert('Editar publicación con ID: ' + localStorage.getItem("idComida"));
    
    this.router.navigate(['/actualizarComida']); //PARA QUE ESTO FUNCIONE SE DEBE PONER EN EL CONSTRUCTOR JUNTO AL SERVICIO.
  
  }
  

 


}