import { Component } from '@angular/core';
import { Router, RouterModule, RouterOutlet } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, RouterModule, CommonModule],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {

  showLink: boolean = true; // CREADO PARA ELIMINAR EL ERROR QUE MUESTRA ETIQUETA <A> EN PAGINAS DONDE YA REDIRIGIO.
  
  private ignorar: string[] = ['/comidas', '/crearComida','/actualizarComida','/comidaEspecifica']; // Lista de rutas a excluir
  
  constructor(private router: Router) {
    // Oculta el enlace si la URL actual está en las rutas excluidas.
    this.router.events.subscribe(() => {
      const currentUrl = this.router.url;
      this.showLink = !this.ignorar.includes(currentUrl);
    });
  }

}
