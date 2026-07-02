<x-admin-layout title="Add Case Study">
    @include('admin.case-studies._form', [
        'caseStudy' => $caseStudy,
        'action' => route('admin.case-studies.store'),
        'method' => 'POST',
        'submitLabel' => 'Create Case Study',
    ])
</x-admin-layout>
