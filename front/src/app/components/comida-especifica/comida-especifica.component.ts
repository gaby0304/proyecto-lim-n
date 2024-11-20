import { Component, OnInit } from '@angular/core';
import { ComidasService } from '../../services/comidas.service';  // Importa el servicio
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';  // Importa CommonModule
@Component({
  selector: 'app-comida-especifica',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './comida-especifica.component.html',
  styleUrl: './comida-especifica.component.scss'
})
export class ComidaEspecificaComponent {

  comidaEspecifica: any = {};  // Para almacenar los detalles de la comida

  constructor(private comidasService: ComidasService, private router: Router) {}

  ngOnInit(): void {
    // Obtener el ID de la comida desde localStorage
    const idComida = localStorage.getItem('idComida');
    
    if (idComida) {
      // Llamar al servicio para obtener la comida con el ID
      this.comidasService.getComidaId(Number(idComida)).subscribe(
        (resp) => {
          if (resp && resp.data && resp.data.length > 0) {
            this.comidaEspecifica = resp.data[0];  // Asumiendo que el primer elemento es el correcto
          }
        },
        (error) => {
          console.error('Error al obtener los detalles de la comida:', error);
        }
      );
    }
  }



    // ESTO DEBE SER MODIFICADO POR UN ROUTERLINK CUANDO ESTE TODO EL PROYECTO ARMADO
    goBack(): void {
      localStorage.clear();
      window.history.back();
    }
     
}
