import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms'; 
import { CommonModule } from '@angular/common'
import { ComidasService } from '../../services/comidas.service';


@Component({
  selector: 'app-crearcomidas',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './crearcomidas.component.html',
  styleUrl: './crearcomidas.component.scss'
})
export class CrearcomidasComponent {
 
  publicacion = {
    nombre_comida: '',
    descripcion_comida: '',
    imagen: '',
    usuario_id: 1,  // ESTATICO PORQUE AUN NO TENEMOS USUARIOS DE MANERA FUNCIONAL.
    elaboracion: ''
  };

  constructor(private comidasService: ComidasService) {}

  // Método para enviar los datos del formulario al servidor
  crearPublicacion() {
    console.log('Nueva publicación:', this.publicacion);
    this.comidasService.crearComida(this.publicacion).subscribe(
      (response) => {
       alert('Comida creada con éxito:');
      this.FormateoTexto();
      
      },
      (error) => {
        console.error('Error al crear la comida:', error);
        alert('Ha ocurrido un error. Vuelva a intentarlo por favor');
        this.FormateoTexto();
      }
    );
  }
 
 
 
 
 
 // Borra texto del formulario para decir si se hizo la insercion o no
 FormateoTexto(): void{
  this.publicacion.nombre_comida = '';
  this.publicacion.descripcion_comida = '';
  this.publicacion.elaboracion = '';
  this.publicacion.imagen = '';
 }
 
 
 
  // debe cambiarse por routerlink cuando ya la app este consolidada. 
  goBack(): void {
    window.history.back();
  }

  
}
