import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class ComidasService {
  private baseUrl = 'http://localhost:9000/api/comidas'; // ruta de donde consumo la API DE LA VARA 

  constructor(private http: HttpClient) { }


 // TODOS LOS SERVICIOS SON APLICABLES PARA LOS DEMÁS MODULOS SOLO ES CAMBIARLE EL NOMBRE DEL MODULO ESPECIFICO ARRIBA :)



  // Método para obtener todas las comidas (read general)
  getComidas(): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/read`);
  }


  // Método para filtrar comidas
  filtrarComidas(nombre: string): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/filtro/${nombre}`);
  }

  // CREATE DE COMIDAS
  crearComida(comida: any): Observable<any> {
    return this.http.post<any>(`${this.baseUrl}`, comida);
  }

  
 
   // read con id especifico
   getComidaId(id: number): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/read/${id}`);  // URL con el ID
  }

  

  updateComida(id: number, comida: any): Observable<any> {
    return this.http.put<any>(`${this.baseUrl}/${id}`, comida);  // URL con el ID
  }





}






