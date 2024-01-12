
library(shiny)
library(dplyr)
library(shinythemes)
library(ggplot2)

data = read.csv("sae.csv", sep = ";")

annees_disponibles = unique(data$annee)
mois_disponibles = unique(data$mois)

ui = fluidPage(
  theme = shinytheme("flatly"),
  
  tabsetPanel(
    tabPanel("Analyse statistique",
             wellPanel(
               style = "display: flex; justify-content: center; height: 15vh",
               titlePanel("Analyse du nombre de Visiteurs")
             ),
             sidebarLayout(
               sidebarPanel(
                 h4("Période de début :"),
                 fluidRow(
                   column(6,
                          selectInput("mois_debut", "Mois :", choices = mois_disponibles)
                   ),
                   column(6,
                          selectInput("annee_debut", "Année :", choices = annees_disponibles)
                   )
                 ),
                 
                 h4("Période de fin :"),
                 fluidRow(
                   column(6,
                          selectInput("mois_fin", "Mois :", choices = mois_disponibles)
                   ),
                   column(6,
                          selectInput("annee_fin", "Année :", choices = annees_disponibles)
                   )
                 ),
                 
                 br(),
                 checkboxInput("moyenne", "Moyenne"),
                 checkboxInput("mediane", "Médiane"),
                 checkboxInput("ecart_type", "Écart type"),
                 
                 br(),
                 actionButton("calculer", "Calculer", class = "btn-primary"),
               ),
               
               mainPanel(
                 wellPanel(
                   h4("Résultats :"),
                   uiOutput("resultat_stats"),
                   plotOutput("graphique")
                 )
               )
             ),
             
             wellPanel(
               h4("Interprétation des données :"),
               uiOutput("interpretation_stats")
             ),
    ),
    tabPanel("Probabilité",
             wellPanel(
               style = "display: flex; justify-content: center; height: 15vh",
               titlePanel("Nombres de visiteurs attendus a l'avenir")
             ),
             sidebarLayout(
               sidebarPanel(
                 h4("Calculer la probabilité que le nombre de visiteur pour le prochain mois soit inférieur ou supérieur à la moyenne :"),
                 fluidRow(
                   column(6,
                          selectInput("mois_future", "Mois :", choices = mois_disponibles)
                   ),
                   column(6,
                          selectInput("position", "Position :", choices = c("inférieur", "supérieur"))
                   ),
                   column(12,
                          actionButton("calculer_probabilite", "Calculer", class = "btn-primary")
                   )
                 ),
                 wellPanel(
                   textOutput("probabilite_result")
                 )
               ),
               mainPanel(
                 style = "text-align: center;",
                 wellPanel(
                   h4("Graphique des moyennes et des médiannes du nombre de visiteur pour chaque mois toutes années confondue :"),
                   plotOutput("graphique_moyenne")
                 )
               )
             )
    ),
    tabPanel("Modification des données",
             wellPanel(
               style = "display: flex; justify-content: center; height: 15vh",
               titlePanel("Vous pouvez ajouter, modifier et supprimer des données :")
             ),
             div(
               style = "display: flex; justify-content: space-around; padding: 10px;",
               
               div(
                 style = "display: flex; align-items: center; flex-direction: column; padding: 10px; margin: 10px; background-color: #ECF0F1;",
                 h4("Ajouter des données"),
                 br(),
                 fluidRow(
                   column(6,
                          selectInput("ajouter_mois", "Mois :", choices = mois_disponibles)
                   ),
                   column(6,
                          numericInput("ajouter_annee", "Année :", value = 2024)
                   )
                 ),
                 numericInput("ajouter_visiteur", "Nombre de visiteur :", value = 0),
                 actionButton("ajouter", "Ajouter", class = "btn-primary mt-3")
               ),
               
               div(
                 style = "display: flex; align-items: center; flex-direction: column; padding: 10px; margin: 10px; background-color: #ECF0F1",
                 h4("Modifier des données"),
                 br(),
                 fluidRow(
                   column(6,
                          selectInput("modifier_mois", "Mois :", choices = mois_disponibles)
                   ),
                   column(6,
                          selectInput("modifier_annee", "Année :", choices = annees_disponibles)
                   )
                 ),
                 numericInput("modifier_visiteur", "Nombre de visiteur :", value = 0),
                 actionButton("modifier", "Modifier",class = "btn-primary mt-3")
               ),
               
               div(
                 style = "display: flex; align-items: center; flex-direction: column; padding: 10px; margin: 10px; background-color: #ECF0F1",
                 h4("Supprimer des données"),
                 br(),
                 fluidRow(
                   column(6,
                          selectInput("supprimer_mois", "Mois :", choices = mois_disponibles)
                   ),
                   column(6,
                          selectInput("supprimer_annee", "Année :", choices = annees_disponibles)
                   )
                 ),
                 actionButton("supprimer", "Supprimer", class = "btn-primary mt-3")
               )
             )
    )
  )
)

#__________________________________Server________________________________________#

server = function(input, output) {
  
  #_____________________________________ Partie statistique______________________________________#
  
  moyenne_visiteurs = NULL
  mediane_visiteurs = NULL
  ecart_type_visiteurs = NULL
  
  observeEvent(input$calculer, {
    
    mois_debut_numerique = as.integer(factor(input$mois_debut, levels = month.name))
    mois_fin_numerique = as.integer(factor(input$mois_fin, levels = month.name))
    
    if (((mois_debut_numerique >= mois_fin_numerique) && (input$annee_debut < input$annee_fin)) || ((mois_debut_numerique < mois_fin_numerique) && (input$annee_debut <= input$annee_fin))) {
      periode_select = filter(data,
                              (annee > input$annee_debut | (annee == input$annee_debut & as.integer(factor(mois, levels = month.name)) >= as.integer(factor(input$mois_debut, levels = month.name)))) &
                                (annee < input$annee_fin | (annee == input$annee_fin & as.integer(factor(mois, levels = month.name)) <= as.integer(factor(input$mois_fin, levels = month.name))))
      )
      
      selected_stats = character(0)
      
      if (input$moyenne) {
        moyenne_visiteurs = mean(periode_select$nombre_visiteurs)
        moyenne_visiteurs = round(moyenne_visiteurs, 1)
        selected_stats = c(selected_stats, paste("Moyenne :", moyenne_visiteurs,"\n"))
      }
      
      if (input$mediane) {
        mediane_visiteurs = median(periode_select$nombre_visiteurs)
        mediane_visiteurs = round(mediane_visiteurs, 1)
        selected_stats = c(selected_stats, paste("Médiane :", mediane_visiteurs,"\n"))
      }
      
      if (input$ecart_type) {
        ecart_type_visiteurs = sd(periode_select$nombre_visiteurs)
        ecart_type_visiteurs = round(ecart_type_visiteurs, 1)
        selected_stats = c(selected_stats, paste("Écart type :", ecart_type_visiteurs,"\n"))
      }
      
      # Affichage des statistiques sélectionnées
      output$resultat_stats = renderUI({
        tagList(
          if (!is.null(moyenne_visiteurs)) {
            div(
              style = "border: 2px solid red; padding: 10px; margin-bottom: 10px;",
              HTML(paste("Moyenne :", moyenne_visiteurs, "<br>"))
            )
          },
          if (!is.null(mediane_visiteurs)) {
            div(
              style = "border: 2px solid blue; padding: 10px; margin-bottom: 10px;",
              HTML(paste("Médiane :", mediane_visiteurs, "<br>"))
            )
          },
          if (!is.null(ecart_type_visiteurs)) {
            div(
              style = "border: 2px solid orange; padding: 10px; margin-bottom: 10px;",
              HTML(paste("Écart type :", ecart_type_visiteurs, "<br>"))
            )
          }
        )
      })
      
      # Création du graphique
      output$graphique = renderPlot({
        gg = ggplot(periode_select, aes(x = reorder(mois, as.integer(factor(mois, levels = month.name))), y = nombre_visiteurs, fill = as.factor(annee))) +
          geom_bar(stat = "identity", position = "dodge") +
          labs(title = "Nombre de visiteurs par mois",
               x = "Mois",
               y = "Nombre de visiteurs") +
          theme_minimal()
        
        if (!is.null(moyenne_visiteurs)) {
          gg = gg + geom_hline(yintercept = moyenne_visiteurs, color = "red", size = 1.5)
        }
        
        if (!is.null(mediane_visiteurs)) {
          gg = gg + geom_hline(yintercept = mediane_visiteurs, color = "blue", size = 1.5)
        }
        
        if (!is.null(ecart_type_visiteurs)) {
          gg = gg + geom_hline(yintercept = ecart_type_visiteurs, color = "orange", size = 1.5)
        }
        
        gg = gg + geom_text(aes(label = nombre_visiteurs, group = as.factor(annee)),
                            position = position_dodge(width = 0.9),
                            vjust = -0.5,
                            size = 5) +
          scale_fill_discrete(name = "Années")
        
        print(gg)
      })
      
      output$interpretation_stats = renderUI({
        interpretations <- character(0)  # Initialisation d'un vecteur de caractères vide
        
        if (!is.null(moyenne_visiteurs) && !is.null(mediane_visiteurs) && !is.null(ecart_type_visiteurs)) {
          if (moyenne_visiteurs > mediane_visiteurs) {
            interpretations <- c(interpretations, "La moyenne est supérieure à la médiane, c'est-à-dire qu'il y a une concentration plus importante de valeurs basses qui tirent la moyenne vers le bas. \n")
          }
          if (moyenne_visiteurs < mediane_visiteurs) {
            interpretations <- c(interpretations, "La moyenne est inférieure à la médiane, c'est-à-dire qu'il y a une concentration plus importante de valeurs élevées qui tirent la moyenne vers le haut. \n")
          }
          if (ecart_type_visiteurs > (moyenne_visiteurs/2)) {
            interpretations <- c(interpretations, "Le résultat de l'écart type nous montre que certaines données sont très éloignées de la moyenne. \n")
          }
          if (ecart_type_visiteurs < mediane_visiteurs/2) {
            interpretations <- c(interpretations, "Le résultat de l'écart type nous montre que certaines données sont très éloignées de la médiane. \n")
          }
        } else {
          interpretations <- c(interpretations, "Veuillez sélectionner plus de calcul de statistiques pour avoir une interprétation plus approfondie. \n")
        }
        
        renderText(paste(interpretations, collapse = "\n"))
      })
    
    } else {
      output$resultat_stats = renderText("Veuillez sélectionner une période valide")
    }
  })
  
  #_____________________________________ Partie Modification de données_____________________________________#
  
  observeEvent(input$ajouter,{
    mois_ajout <- input$ajouter_mois
    annee_ajout <- input$ajouter_annee
    visiteur_ajout = input$ajouter_visiteur
    
    date_existante <- filter(data, mois == mois_ajout & annee == annee_ajout)
    
    if (nrow(date_existante) > 0) {
      
      showNotification("La date existe déjà. Veuillez sélectionner une autre date.", type = "warning")
    } else {
      nouvelles_donnees <- data.frame(
        mois = mois_ajout,
        nombre_visiteurs = visiteur_ajout,
        annee = annee_ajout,
        data = read.csv("sae.csv", sep = ";")
      )
      write.table(nouvelles_donnees, "sae.csv", sep = ";", col.names = !file.exists("sae.csv"), append = TRUE, row.names = FALSE, quote = FALSE)
      showNotification("Données ajoutées avec succès !", type = "message")
    }
  })
  
  observeEvent(input$modifier, {
    mois_modifier <- input$modifier_mois
    annee_modifier <- input$modifier_annee
    visiteur_modifier <- input$modifier_visiteur
    
    date_existante_modif <- filter(data, mois == mois_modifier & annee == annee_modifier)
    
    if(nrow(date_existante_modif) > 0){
      data[data$mois == mois_modifier & data$annee == annee_modifier, "nombre_visiteurs"] <- visiteur_modifier
      
      showNotification("Le nombre de visiteur a bien était changer !", type = "message")
    }else {
      showNotification("La date choises n'existe pas. Veuillez sélectionner une autre date.", type = "warning")
    }
  })
  
  observeEvent(input$supprimer, {
    mois_supprimer <- input$supprimer_mois
    annee_supprimer <- input$supprimer_annee
    
    date_existante_suppr <- filter(data, mois == mois_supprimer & annee == annee_supprimer)
    
    if(nrow(date_existante_suppr) > 0){
      data <- data[!(data$mois == mois_supprimer & data$annee == annee_supprimer), ]
      
      write.table(data, "sae.csv", sep = ";", row.names = FALSE, quote = FALSE, col.names = TRUE)
      
      showNotification("Ces données ont bien était supprimer !", type = "message")
    }else {
      showNotification("La date choises n'existe pas. Veuillez sélectionner une autre date.", type = "warning")
    }
  })
  
  #________________________________________ Partie probabilité __________________________________________________#
  
  observeEvent(input$calculer_probabilite, {
    
    donnees_mois_choisi <- filter(data, mois == input$mois_future)
    print(donnees_mois_choisi)
    
    moyenne_mois_choisi <- mean(donnees_mois_choisi$nombre_visiteurs)
    ecart_type_mois_choisi <- sd(donnees_mois_choisi$nombre_visiteurs)
    
    if (input$position == "inférieur") {
      probabilite <- pnorm(moyenne_mois_choisi, mean = moyenne_mois_choisi, sd = ecart_type_mois_choisi)
    } else {
      probabilite = 1 - pnorm(moyenne_mois_choisi, mean = moyenne_mois_choisi, sd = ecart_type_mois_choisi)
    }
    
    # Affichage du résultat avec un arrondi plus précis
    output$probabilite_result = renderText({
      paste("La probabilité que le nombre de visiteurs du prochain mois choisi soit", input$position, "à la moyenne est de",
            round(probabilite * 100, 5), "%.")
    })
  })
  
  output$graphique_moyenne = renderPlot({
    moyennes_medians_par_mois = data %>%
      group_by(mois) %>%
      summarize(moyenne = mean(nombre_visiteurs), mediane = median(nombre_visiteurs))
    
    ggplot(moyennes_medians_par_mois, aes(x = mois)) +
      geom_point(aes(y = moyenne, color = "Moyenne"), size = 5) +
      geom_point(aes(y = mediane, color = "Médiane"), size = 5) +
      geom_segment(aes(x = mois, xend = mois, y = 0, yend = moyenne), color = "gray", size = 1) +
      geom_segment(aes(x = mois, xend = mois, y = 0, yend = mediane), color = "gray", size = 1) +
      geom_text(aes(y = moyenne, label = round(moyenne, 1)), vjust = -0.5, hjust = 1.5, color = "black", size = 3.5) +
      geom_text(aes(y = mediane, label = round(mediane, 1)), vjust = -0.5, hjust = -0.5, color = "black", size = 3) +
      labs(title = "Moyennes et Médianes du Nombre de Visiteurs par Mois",
           x = "Mois",
           y = "Nombre de Visiteurs") +
      scale_color_manual(values = c("Moyenne" = "red", "Médiane" = "blue"), name = "Légende") +
      theme_minimal()
  })
}

shinyApp(ui = ui, server = server)