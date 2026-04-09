<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'email' => 'required|email',
            'owner_name' => 'required|string|max:255',
            'owner_mobile' => 'required|string|max:50',
            'owner_email' => 'required|email',
            'contact_name' => 'required|string|max:255',
            'contact_mobile' => 'required|string|max:50',
            'contact_email' => 'required|email',
        ];
    }

    public function index()
    {
        $companies = Company::where('is_active', 1)->orderBy('name')->get();
        return view('admin.companies.index', ['companies' => $companies]);
    }

    public function inactive()
    {
        $companies = Company::where('is_active', 0)->orderBy('name')->get();
        return view('admin.companies.inactive', ['companies' => $companies]);
    }

    public function show(Company $company)
    {
        return view('admin.companies.show', ['company' => $company, 'product' => $company->products()->orderBy('name_en')->get()]);
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        Company::create($data);

        return redirect()->route('companies.index')->with('success', 'Company success added');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', ['company' => $company]);
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate($this->rules());
        $company->update($data);

        return redirect()->route('companies.show', $company)->with('success', 'Company data success updated');
    }

    public function toggleactive(Company $company)
    {
        $company->update(['is_active' => !$company->is_active]);
        $message = $company->is_active ? 'Company activated' : 'Company deactivated';

        return back()->with('success', $message);
    }
}
