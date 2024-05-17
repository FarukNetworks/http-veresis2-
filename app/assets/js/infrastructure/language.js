// Translatable strings in application Lppm
// Slovenian version
var language = function () {
        return {
            // used on many diferent forms
            common: {
                dataRefreshed: 'Podatki osveženi',
                dataError: 'Napaka'
            },
            // used in infrastructure/custom.formatters.js 
            stateFormatters: {
                order: {
                    newOrder: 'Nov',
                    hasNumberInterval: 'Dodeljen št. prostor',
                    partiallyPocessed: 'Delno obdelan',
                    processed: 'Obdelan',
                    missingNumberSpace: 'Manjka št. prostor',
                    cancelled: 'Storniran',
                    shipped: 'Odposlan',
                    partiallyShipped: 'Delno odposlan'
                },
                orderCssClass: {
                    newOrder: 'nov',
                    hasNumberInterval: 'dodeljen-prostor',
                    partiallyPocessed: 'delno-obdelan',
                    processed: 'obdelan',
                    missingNumberSpace: 'manjka-prostor',
                    cancelled: 'storniran',
                    shipped: 'odposlan',
                    partiallyShipped: 'delno-odposlan'
                },
                orderDetailCssClass: {
                    newOrder: 'nov-detail',
                    hasNumberInterval: 'dodeljen-prostor-detail',
                    partiallyPocessed: 'delno-obdelan-detail',
                    processed: 'obdelan-detail',
                    missingNumberSpace: 'manjka-prostor-detail',
                    cancelled: 'storniran-detail',
                    shipped: 'odposlan-detail',
                    partiallyShipped: 'delno-odposlan-detail'
                },
                productionOrder: {
                    created: 'Kreiran',
                    assignedToMachine: "Razp. na prešo",
                    readyForProduction: "Prip. na proizvodnjo",
                    inProduction: "V proizvodnji",
                    produced: "Proizveden",
                    shipped: "Dobavljeno",
                    productionPaused: "Ustavljen",
                    cancelled: "Storniran",
                    allPressed: "Tablice odtisnjene"
                },
                plate: {
                    newPlate: 'Nova',
                    knownEanCode: "Znana EAN koda",
                    pressed: "Odtisnjena",
                    stickerIsGlued: "Nalepka nalepljena",
                    packed: "Zapakirana",
                    cancelled: "Stornirana",
                    rejected: "Zavrnjena"
                },
                bag: {
                    newBag: 'Nova',
                    packed: "Zapakirana",
                    labelIsPrinted: "Etiketa natisnjena",
                    shipped: "Odpremljena",
                    cancelled: "Stornirana",
                    rejected: "Zavrnjena",
                    rejectionreplaced: "Zavrnitev nadomeščena"
                },
                package: {
                    unknown: 'neznano',
                    new: "nov",
                    inProcess: "v procesu",
                    destroyed: "uničen",
                    cancelled: "preklican",
                },
                selfAdhesivePrint: {
                    newSelfAdhesivePrint: 'Nova pola',
                    pdfCreated: 'Izdelan PDF',
                    printed: 'Natisnjeno',
                    cancelled: 'Storniran'
                },
                dispatchFormmater: {
                    New: 'Nov',
                    deliveryNoteAssigned: 'Dobavnica dodeljena',
                    dispatched: 'Odpremljen',
                    cancelled: 'Preklican'
                },
                box: {
                    New: 'Nov',
                    Created: 'Kreiran',
                    Closed: 'Zaprt',
                    PostalLabelPrinted: 'Poštna nalepka natisnjena',
                    AssignedToDispatch: 'Dodeljen odpremi',
                    Cancelled : 'Preklican'
                }
            },
            orderTypeFormatter: {
                Serie: 'Serije',
                Service: 'Servis'
            },
            processAlgorythmFormatter: {
                standardProcess: 'Prešanje',
                selfAdhesiveProcess: 'Samolepilne'
            },
            productionMachineFormatter: {
                press: 'Stiskalnica',
                stikers: 'Lepljenje grbov',
                packing: 'Pakiranje',
                labels: 'Etiketiranje',
                dispatching: 'Odprema',
                robot: 'Robot',
                plateDestroyer: 'Uničevalec'
            },
            //use in vm.importorder.js
            importOrders: {
                modalTitleDetailOrder: 'Podrobnosti naročila št.',
                modalTitleDetailOrderRegNo: 'reg. št.',
                modalTitleDetailOrderDate: 'z datumom:',
                columnNameOrderNumber: 'Št. naročila',
                columnNameOrderDate: 'Datum',
                columnNameCustomerId: 'Naročnik (MRVL šifra)',
                columnNameCustomer: 'Naročnik',
                columnNameQuantity: 'Količina',
                columnNameIntervalFrom: 'Interval od',
                columnNameIntervalTo: 'Interval do',
                columnNameStatus: 'Status',
                orderCandidatesStates: {
                    success: 'USPEŠNO',
                    withError: 'NAPAKE',
                    duplicate: 'PODVOJEN'
                }
            },
            // used in vm.numberSpace.js
            numberSpace: {
                editLinkInGrid: 'Uredi',
                columnNameLegislationPattern: 'Zak.',
                columnNamePattern: 'Vzorec',
                columnNameDistrictName: 'Območje',
                columnNameDistrictCode: 'A.',
                columnNamePlateNumberFrom: 'Od',
                columnNamePlateNumberTo: 'Do',
                columnNameCount: 'Št.',
                columnNameProcessAlgorythm: 'Rezerv. za: ',
                columnNameAction: 'Akcija',
                columnNameOrderNumber: 'Naročilo',
                columnNameOrderDate: 'Datum n.',
                columnNameCustomerName: 'Naročnik',
                columnNameQuantity: 'Kol.',
                columnNameProductTypeName: 'Produkt',
                columnNameUsed: 'Upor: ',
                msgValidationQuantity: 'Količina je večja od razpoložljive!'
            },
            // used in vm.numberSpace.js
            numberSpaceProcessAlgorythm: {
                standardProcessName: "standardne kovinske",
                selfAdhesiveProcessName: "samolepilne tablice",
                notDefinedProcessName: "vse tablice"
            },
            codeLists: {
                common: {
                    columnNameIsActive: 'Omogočen',
                    confirmMessage: 'Ste prepričani, da želite'
                },
                //used in vm.customer.js
                customer: {
                    columnNameExternalCode: 'Šifra Mrvl',
                    columnNameName: 'Naziv',
                    columnNameAddress: 'Naslov',
                    columnNameCity: 'Kraj',
                    messageCustomerNotExistsInErp: 'Stranka ne obstaja v Pantheonu',
                    customerNotSelected: 'Izberite stranko',
                    popupTitleAddCustomer: 'Nov kupec',
                    popupTitleEditCustomer: 'Urejanje kupca',
                    confirmMsgDeactCustomer: 'Ste prepričani, da želite deaktivirati kupca?',
                    confirmMsgActivCustomer: 'Ste prepričani, da želite aktivirati kupca?',
                    confirmMsgDeleteCustomer: 'Ste prepričani, da želite zbrisati kupca?'
                },
                //used in vm.administrativeunits.js
                administrativeUnits: {
                    columnNameName: 'Ime',
                    columnNameCode: 'Šifra UE',
                    columnNameStickerName: 'Grb',
                    columnNameDistrict: 'Okraj',
                    confirmMsgActivAdminUnit: 'Ste prepričani, da želite aktivirati upravno enoto?',
                    confirmMsgDeactAdminUnit: 'Ste prepričani, da želite deaktivirati upravno enoto?',
                    popupTitleAddAdminUnit: 'Nova upravna enota',
                    popuptitleEditAdminUnit: 'Urejanje upravne enote'
                }
            },

            //used in vm.account.js
            account: {
                errorMsgOnChangePassword: 'Napaka pri spremembi gesla!'
            },

            //used in vm.allowedPattern.js
            allowedPattern: {
                columnNameLegislationPattern: 'Predpisan vzorec',
                columnNameDescription: 'Opomba',
                columnNameNumberFrom: 'Od številke',
                columnNameCount: 'Vzorcev',
                columnNameExternalCode: 'Tipi tablic',
                popupTitleAddAllowedPattern: 'Dodajanje novega vzorca',
                popupTitleEditAllowedPattern: 'Urejanje vzorca',
                confirmMsgActivAllowedPattern: 'Ste prepričani, da želite aktivirati zakonski vzorec?',
                confirmMsgDeactAllowedPattern: 'Ste prepričani, da želite deaktivirati zakonski vzorec?'
            },

            // used in vm.deliveryNotes.js and vm.deliveryNotes.import.js
            deliveryNotes:
            {
                columnNameExport: 'Izvozi',
                columnNameID: 'ID dobavnice',
                columnNamePantheonNote: 'Pantheon dobavnica',
                columnNamePantheonOrder: 'Pantheon naročilo',
                columnNameDate: 'Datum dobavnice',
                columnNameCustomer: 'Naročnik',
                columnNameQuantity: 'Količina',
                columnNameProduct: 'Produkt',
                columnNameDistrict: 'Območje',
                columnNameSticker: 'Grb',
                columnNameFrom: 'Od',
                columnNameTo: 'Do',
                columnNameNumber: 'Kosov',
                columnNameExportDate: 'Izvoženo',
                columnNameReason: 'Razlog',
                captionDeliveryNotes: 'Uvožene dobavnice',
                captionDeliveryNotesSuccess: 'Uspešno uvožene dobavnice',
                captionFaultyDeliveryNotes: 'Dobavnice z napako',
                errorChosenDeliveryNotes: 'Ni izbranih dobavnic!',
                columnNameDispatchNumber: 'Št. odpreme',
                columnNameProductionOrderNumber: 'Št. proizv. nal.',
                columnNamePlateType: 'Tablica',
                nothingForExport: "Ni neizvoženih dobavnic"
            },

            // used in vm.districts.js
            districts:
            {
                columnNameDistrictNo: 'No',
                columnNameDistrictCode: 'Naziv',
                columnNameDistrictName: 'Ime',
                columnNameDistrictUnits: 'Upravne enote',
                columnNameDistrictIsActive: 'Omogočen',
                confirmMsgActivDistrict: 'Ste prepričani, da želite aktivirati okraj?',
                confirmMsgDeactDistrict: 'Ste prepričani, da želite deaktivirati okraj?',
                popupTitleAddDistrict: 'Nov okraj',
                popupTitleEditDistrict: 'Urejanje okraja '
            },

            // used in vm.fonts.js
            fonts:
            {
                popupTitleAddFont: 'Dodajanje nove tipografije',
                popupTitleEditFont: 'Urejanje tipografije ',
                alertNewGlyph: 'Dodana je bila nova črka, osveži prikaz!'
            },

            // used in vm.forbiddencodes.js
            forbiddenCodes:
            {
                columnNameValue: 'Niz',
                columnNameDescription: 'Opis',
                columnNameIsActive: 'Omogočen',
                confirmMsgActivForbiddenCode: 'Ste prepričani, da želite aktivirati prepovedan niz?',
                confirmMsgDeactForbiddenCode: 'Ste prepričani, da želite deaktivirati prepovedan niz?',
                popupTitleAddForbiddenCode: 'Nov prepovedan niz',
                popupTitleEditForbiddenCode: 'Urejanje prepovedanega niza '
            },

            // used in vm.numberSpaceGenerator.js
            numberSpaceGenerator:
            {
                newRecords: ' novih zapisov',
                confirmMsgReserveForProductType: 'Pozor: številski prostor bo REZERVIRAN za izbrani tip produkta. Ali želite nadaljevati s shranjevanjem?'
            },

            // used in vm.order.*.js
            order:
            {
                columnNameOrderNumber: 'Št. naročila',
                columnNameOrderDate: 'Datum',
                columnNameOrderCustomer: 'Naročnik',
                columnNameOrderQuantity: 'Količina',
                columnNameOrderProduct: 'Produkt',
                columnNameOrderProcess: 'Proces',
                columnNameOrderDistrict: 'Območje',
                columnNameOrderUnitName: 'Grb',
                columnNameOrderMissingNs: 'Manjka št.',
                columnNameOrderIntervalFrom: 'Od',
                columnNameOrderIntervalTo: 'Do',
                columnNameOrderState: 'Stanje',
                columnNameOrderType: 'Tip',
                columnNameOrderCancel: 'Storno',
                columnNameOrderCallOff: 'Odpoklic',
                columnLinkCancelOrderNumberSpace: 'Sprosti št.p.',
                columnLinkOrderCancel: 'Storniraj',
                columnNameNumberSpace: 'Številski prostor',
                linkAssignNumberSpace: 'Določi',
                popupTitleOrderCancelReason: 'Vpišite razlog stornacije naročila ',
                popupTitleOrderCancelReasonLimit: ' (vsaj 10 znakov):',
                popupAlertOrderCancelCharLimit: 'Razlog stornacije mora vsebovati vsaj 10 znakov!',
                msgOnCallOfCustomsOrders: {
                    mustSelectMinOneOrder: 'Izberite vsaj eno naročilo!',
                    mustSelectMinOnePressMachine: 'Izberite vsaj eno prešo!',
                    modalProgressMsgCallOfOrders: 'Izvajanje akcije - odpoklic servisov...',
                    gettingDataForNewReports: 'Pridobivanje podatkov o novih poročilih...'
                },
                msgOnCallOfOrdersSeries: {
                    mustSelectMinOneOrder: 'Izberite vsaj eno naročilo!',
                    mustSelectMinOnePressMachine: 'Izberite vsaj eno prešo!',
                    modalProgressMsgCallOfOrders: 'Izvajanje akcije - odpoklic serij...',
                    gettingDataForNewReports: 'Pridobivanje podatkov o novih poročilih...'
                },
                msgValidationProductionQuantity: 'Količina je večja od razpoložljive!',
                //assign number space to order
                numberSpacesGrid: {
                    linkSplit: 'razstavi',
                    linkApply: 'uporabi',
                    msgOnRefreshGridIfOrderNoSelected: 'Najprej izberite naročilo...',
                    msgOnMissingQuantity: {
                        toMuchSelected: '  Preveč izbranih:  ',
                        allIntervalsAssigned: '  Vsi intevali so določeni  ',
                        stillMissing: '  Manjka še:  '
                    },
                    columnNamePlateNumberFrom: 'Od',
                    columnNamePlateNumberTo: 'Do',
                    columnNameCount: 'Število',
                    columnNameReservation: 'Rezerv.',
                    columnNamePrint: 'Print',
                    columnBameActionSplit: 'Akcija'
                }
            },

            //used in vm.productionorders.js
            productionOrders: {
                columnNameProductionOrderNo: 'No',
                columnNameCustomerCode: 'Šifra kupca',
                coumnNameCustomerName: 'Kupec',
                columnNameOrderNumber: 'Št. naročila',
                columnNameOrderDate: 'Datum naročila',
                columnNameOrderType: 'Tip naročila',
                columnNameOrderState: 'Stat. naročila',
                columnNameIntervalFrom: 'Int. od',
                columnNameIntervalTo: 'Int. do',
                columnNameQuantity: 'Kol.',
                columnNameProductTypeName: 'Produkt',
                columnNamePlateTypeName: 'Tablica',
                columnNameErpOrderCode: 'Št. nar. pantheon',
                columnNameProductionOrderState: 'Stanje  PN',
                columnNameProductionOrderReportNo: 'Št. izpisa',
                columnNameAdministrativeUnitName: 'Grb',
                columnNameDeliveryNoteErpCode: 'Dobavnica',
                columnNameDeliveryNoteErpDate: 'Datum dobavnice',
                columnNameFont: 'Orodje',
                prodOrderModel: {
                    toolTipState: 'Stanje ',
                    toolTipPriority: ', prioriteta: ',
                    created: ', ustvarjeno: ',
                    states: {
                        none: "neznano stanje: ",
                        assignToMachine: "dodeljeno, ni poslano v proizvodnjo",
                        sendToProduction: "poslano v proizvodnjo",
                        inProduction: "se proizvaja",
                        pausedInProduction: "PAVZA v proizvodnji"
                    },
                    btnActions: {
                        sendToProduction: "Pošlji v proizvodnjo",
                        removeFromProduction: "Umakni iz proizvodnje",
                        forcePause: "Vsili pavzo",
                        returnFromPauseToToProduction: "Vrni v proizvodnjo"
                    }
                }

            },
            //used in producttype.js
            productType: {
                columnNameName: 'Naziv',
                columnNameOfficalName: 'Uradni naziv',
                columnNameErpCodeSeries: 'Šifra Erp serija',
                columnNameErpCodeCustom: 'Šifra Erp servis',
                columnNameDescription: 'Opis',
                columnNamePlateTypes: 'Tablice',
                columnNameFontName: 'Font',
                columnNameProcessAlgorythm: 'Algoritem',
                columnNameExportCode: 'Koda MRVL',
                columnNamePriceForDispatch: 'Cena Pošta',
                columnNamePrefixExceptions: 'Izjeme',
                confirmMsgActiv: 'Ste prepričani, da želite aktivirati produkt?',
                confirmMsgDeact: 'Ste prepričani, da želite deaktivirati produkt?',
                popupTitleAdd: 'Nov produkt',
                popupTitleEdit: 'Urejanje produkta '
            },

            //used in producttypertextimportmapping.js
            productTypeTextMapping: {
                msgBeforeDelete: 'Izberite zapis',
                columnNameProductTypeName: 'Produkt',
                columnNameSubType: 'Podvrta',
                columnNameProductVariant: 'Varianta',
                columnNameProductSet: 'Komplet',
                confirmMsgDelete: 'Ste prepričani da želite zbrisati zapis?'
            },
            productionOrderReportPrint: {
                reportSerialNo: 'Št. izpisa: ',
                reportDate: 'Datum izpisa: ',
                machine: 'Stroj: '
            },
            serviceReports: {
                columnNameSerialNumber: 'Serijska številka',
                columnNameProductionMachine: 'Preša',
                columnNameDateCreated: 'Datum',
                columnNameUserName: 'Ustvaril',
                linkOpen: 'odpri'
            },
            seriesReports: {
                columnNameSerialNumber: 'Serijska številka',
                columnNamePlateType: 'Tip tablice',
                columnNameDateCreated: 'Datum',
                columnNameUserName: 'Ustvaril',
                linkOpen: 'odpri'
            },
            plateType: {
                columnNameName: 'Naziv',
                columnNameCode: 'Koda',
                columnNameDescription: 'Opis',
                columnNameHasSticker: 'Grb',
                columnNameStickerReplacementCharacter: '#',
                columnNameWidth: 'Dimenzije',
                columnNameFontColor: 'Barve',
                columnNameCanCreateInterval: 'Interval?',
                columnNameMachines: 'Preše',
                columnNameExportCode: 'MRVL',
                popUpTitleAdd: 'Nov tip tablice',
                popUpTitleEdit: 'Urejanje tablice ',
                confirmMsgActiv: 'Ste prepričani, da želite aktivirati tip tablice?',
                confirmMsgDeact: 'Ste prepričani, da želite deaktivirati tip tablice?'
            },
            productionMachine: {
                columnNameName: 'Naziv',
                columnNameCode: 'Oznaka',
                columnNameDescription: 'Opis',
                columnNameProductionMachineType: 'Tip stroja',
                confirmMsgActiv: 'Ste prepričani, da želite aktivirati stroj?',
                confirmMsgDeact: 'Ste prepričani, da želite deaktivirati stroj?',
                popUpTitleAdd: 'Nov stroj',
                popUpTitleEdit: 'Urejanje stroja ',
                selected: 'izbrano',
                nonSelected: 'ni izbrano, klikni za izbiro...'
            },
            production: {
                btnTitleMovetoPressMachine: 'prestavi na prešo',
                buttonFormatter: {
                    iconToolTip: {
                        series: 'Serije',
                        service: 'Po naročilu'
                    }
                },
                columnNameOrderNumber: 'Naročilo',
                columnNameOrderDate: 'Datum',
                columnNameCustomer: 'Naročnik',
                columnNameQuantity: 'Količina',
                columnNameProduct: 'Produkt',
                columnNameOrderUnitName: 'Grb',
                columnNamePlateNumberFrom: 'Od',
                columnNamePlateNumberTo: 'Do',
                columnNamePriority: 'Prioriteta',
                columnNameFontType: 'Orodje',
                columnNamePlates: 'Tablic',
                columnNameOrderDistrict: 'Območje',
                columnNameActions: 'Akcije',
                reportOnMachineComulativeCount: 'Kosov'
            },
            rejectBag: {
                stage1: {
                    msgOrderNotContainCorrectPlateNumber: 'Nobeno naročilo ne vsebuje tablice s številko ',
                    msgPlateOrBagIsNotInCorrectState: ', ali pa tablica (vrečka) ni v pravem stanju.',
                    msgOrdersContainsFoundPlateNumber: 'Seznam najdenih naročil s številko '
                },
                stage2: {
                    msgDefault: 'Vnesi registrsko številko in pritisni IŠČI.'
                },
                stage3: {
                    confirmMsgOnReject: 'Ste prepričani, da želite NEPOVRATNO zavrniti tablico s številko '
                }
            },
            selfAdhesivePrint: {
                popupTitleselfAdhesivePrintCancelReason: 'Vpišite razlog stornacije printa ',
                popupTitleselfAdhesivePrintCancelReasonLimit: ' (vsaj 10 znakov):',
                popupAlertselfAdhesivePrintCancelCharLimit: 'Razlog stornacije mora vsebovati vsaj 10 znakov!'
            },
            selfAdhesives: {
                availableSelfAdhesivesGrid: {
                    linkSplit: 'razstavi',
                    linkApply: 'uporabi',
                    columnNamePlateNumberFrom: 'Od',
                    columnNamePlateNumberTo: 'Do',
                    columnNameCount: 'Število',
                    columnNameReservation: 'Rezerv.',
                    columnNamePrint: 'Print',
                    columnBameActionSplit: 'Akcija'
                }
            },
            selfAdhesivePrintList: {
                selfAdhesivesPrintListGrid: {
                    columnNameSequenceNumber: 'Zap. št.',
                    columnNameCreationDate: 'Datum nastanka',
                    columnNameUser: 'Uporabnik',
                    columnNameState: 'Stanje',
                    columnNamePlatesNumber: 'Število tablic'
                }
            },
            selfAdhesivePrintPlatesSchedule: {
                columnNameText: 'Stolpec '
            },
            schedutedTasksNofiferCounters: {
                noProcessCounter: "Opravila v čakalni vrsti, ki še niso bila izvedena",
                retryCounter: "Opravila, ki so že bila neuspešno klicana in so vrnjena v čakalno vrsto",
                failedCounter: "Opravila, ki so bila preklicana, ker je rok za izvedbo POTEKEL"
            },
            orderDetails: {
                linkShowLog: 'Pokaži log',
                plateChanges: 'tablici',
                bagChanges: 'vrečki s tablicami',
                productionOrderChanges: 'proizvodnem nalogu',
                changeLogGrid: {
                    columnNameEntityName: 'Entiteta',
                    columnNameOldState: 'Staro stanje',
                    columnNameNewState: 'Novo stanje',
                    columnNameDescription: 'Opis',
                    columnNameDate: 'Datum',
                    columnNameUser: 'Oseba'
                },
                numberSpacesGrid: {
                    columnNamePlateNumberFrom: 'Od',
                    columnNamePlateNumberTo: 'Do',
                    columnNameCount: 'Število'
                },
                platesGrid: {
                    columnNamePlateNumber: 'Številka',
                    columnNameEan: 'EAN',
                    columnNamePressedTime: 'Odtisnjeno',
                    columnNamePressedUserFullName: 'Odtisnil',
                    columnNameProductionMachineName: 'Preša',
                    columnNamePressToolValidated: 'Orodje',
                    columnNamePlateState: 'Stanje',
                    columnNameAction: 'Akcija'
                },
                bagsGrid: {
                    columnNamePlateNumber: 'Številka',
                    columnNameProductTypeName: 'Produkt',
                    columnNameBagState: 'Stanje',
                    columnNameAction: 'Akcija'
                },
                changeEanCode: {
                    confirmation: 'Ste prepričani, da želite spremeniti EAN kodo?',
                    warning: 'Vpisati morate Ean kodo.'
                }
            },
            numberSpaceReports: {
                columnNameSerialNumber: 'Serijska številka',
                columnNameDateCreated: 'Datum',
                columnNameUserName: 'Ustvaril',
                linkOpen: 'odpri'
            },
            boxType: {
                columnNameSerialNumber: 'Zap. št.',
                columnNameDescription: 'Opis',
                columnNameLength: 'Dolžina škatle',
                columnNameHeight: 'Višina škatle',
                columnNameWidth: 'Širina škatle',
                columnNamePricePerPiece: 'Cena/kos',
                popupTitleEdit: 'Urejanje škatle - ',
                popupTitleAdd: 'Nov tip škatle'
            },
            box: {
                code: 'Koda škatle',
                date: 'Datum',
                isCustom: 'Custom?',
                state: 'Stanje',
                columnNameExternalCode: 'Tipi tablic',
                plannedUtilizationPercent: 'plan. izkoristek',
                actualUtilizationPercent: 'izkoristek',
                boxType: 'Škatla',
                customerName: 'Stranka',
                cancel: 'Preklic',
            },
            dispatch: {
                serialNumber: 'Št.',
                date: 'Datum',
                user: 'Ustvaril',
                state: 'Stanje',
                cancel: 'Preklic',
                deliveryNoteErpCode: 'Dobavnica',
                deliveryNoteDate: 'Dat. dobavnice',
                confirmDispatching: 'Ali želite odpremiti ?',
                postalReportNumber: 'Št.izpisa za pošto',
                dispatchByPost: 'Odprema po pošti',
                confirmDispatchingForToday: 'Ali želite odpremiti današnje ?'
            },
            dispatchDetail: {
                title: "Podrobnosti odpreme št.: "    
            },
            boxDetail: {
                title: "Podrobnosti škatle št.: "
            },
            search: {
                atleast3letters: "Za iskanje vnesite vsaj tri znake."
            },
            destroyedPlatesSearch: {
                atleast2letters: "Za iskanje vnesite vsaj dva znaka."
            }
        };
    };
    var language = new language();