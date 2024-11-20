import { Routes } from '@angular/router';
import { ComidasComponent } from './components/comidas/comidas.component';
import { INICIOComponent } from './components/inicio/inicio.component';
import { CrearcomidasComponent } from './components/crearcomidas/crearcomidas.component';
import { ActualizarcomidasComponent } from './components/actualizarcomidas/actualizarcomidas.component';
import { ComidaEspecificaComponent } from './components/comida-especifica/comida-especifica.component';


export const routes: Routes = [
    {path: 'comidas', component : ComidasComponent},
    {path: 'inicio', component : INICIOComponent},
    {path: 'crearComida', component: CrearcomidasComponent},
    {path: 'actualizarComida', component: ActualizarcomidasComponent},
    {path: 'comidaEspecifica', component: ComidaEspecificaComponent }
];
