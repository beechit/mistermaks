===============
Overview Domain
===============

introduction
------------

in this file is a uml representation of the Domain model with its entities and
relations between the entities.

This model can be rendered online using http://yuml.me

The version is not perfect so feel free to update it.

// Models Base version
[Company|Department|PartnerCompany]
[Person|Employee|CompanyContact|Relative|Contact]
[Address]
[PhoneNumber]
[BankAccount]
[Education]
[ElectronicAddress]
[Asset]
[Licence]
[Group]
[GroupType]
[Absence|Sickness|Leave]
[AbsenceArrangement]
[LeaveBalance]
[Meeting]
[Contract]
[ContractArticle]
[ContractTemplate]
[ContractDocument]
[JobDescription]
[LabourAgreement]
[SalaryScale]
[Wage]
[Message]
[MessageTemplate]
[Document]
[DocumentType]
[Jobposition]
[Link]
[Log]
[Notification]
[Preference]
[Status]
[Workflow]
[Task]
[Minute]
// Relations
[Company]->[Person]
[Person]1->*[Address]
[Person]1->*[PhoneNumber]
[Person]1->*[ElectronicAddress]
[Person]1->*[Education]
[Person]1->*[Licence]
[Person]1->*[Asset]
[Person]1->*[BankAccount]
[Person]1->*[Group]
[Person]1->*[Contract]
[Person]*->*[Meeting]
[Person]1->*[Absence]
[Person]1->*[Message]
[Person]1->*[Document]
[Person]1->*[JobPosition]
[Person]1->*[Notification]
[Person]1->*[JobDescription]
[Person]1->*[Task]
[Person]1->1[LeaveBalance]
[Person]*->1[Preference]
[person]1->*[Minute]
[JobPosition]1->1[JobDescription]
[Company]1->*[Address]
[Company]1->*[PhoneNumber]
[Company]1->*[ElectronicAddress]
[Company]1->*[Asset]
[Company]1->*[BankAccount]
[Company]1->*[Contract]
[Company]1->*[Document]
[Company]1->*[JobPosition]
[Company]1->*[LeaveBalance]
[Company]*->*[Absence]
[Company]1->*[Meeting]
[Contract]*->1[Wage]
[Contract]*->1[Jobdescription]
[JobPosition]*->1[Contract]
[ContractArticle]*->*[Contract]
[ContractTemplate]1->1[Contract]
[LabourAgreement]*->1[Contract]
[SalaryScale]*->1[Contract]
[Contract]1->1[ContractDocument]
[Contract]1->1[Document]
[Group]1->*[GroupType]
[Task]*->*[Link]
[Task]*->[WorkFlow]
[Task]*->*[Action]
[Action]1->*[Task]
[WorkFlow]*->*[Action]
[Absence]1->*[Document]
[Meeting]*->*[Document]
[Meeting]1->*[Minute]
[Minute]1->*[Document]
[Licence]1->*[Document]
[Education]*->[[Document]
[Message]*->*[Document]
[Document]*->[DocumentType]
[Action]->[Message]
[Message]*->1[MessageTemplate]
[Absence]*->[AbsenceArrangement]
[Notification]*->[Link]
[Person]*->[WorkFlow]
[Company]*->[WorkFlow]
[Absence]*->[WorkFlow]
[Meeting]*->[WorkFlow]
[WorkFlow]1->*[Document]
[Contract]*->[WorkFlow]
[LeaveBalance]*->[WorkFlow]
[Task]1->*[Minute]
[Absence]1->*[Minute]
[Contract]1->*[Minute]
[Minute]1->*[Message]
[Company]*->1[Minute]